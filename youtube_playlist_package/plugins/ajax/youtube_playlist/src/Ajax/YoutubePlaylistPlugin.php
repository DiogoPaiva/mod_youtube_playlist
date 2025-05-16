<?php
defined('_JEXEC') or die;

namespace My\Plugin\Ajax\YoutubePlaylist;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Factory;
use Joomla\CMS\Http\HttpFactory;

class YoutubePlaylistPlugin extends CMSPlugin
{
    public function onAjaxYoutube_playlist()
    {
        $app = Factory::getApplication();
        $input = $app->input;

        $playlistId = $input->getString('playlist_id', '');
        $maxResults = $input->getInt('max_results', 5);

        if (empty($playlistId)) {
            return json_encode(['error' => 'Missing playlist ID']);
        }

        // Load API key from secure config
        $apiKey = self::getApiKey();

        $url = "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults= {$maxResults}&playlistId={$playlistId}&key={$apiKey}";

        $http = HttpFactory::getHttp();
        try {
            $response = $http->get($url);
            $data = json_decode($response->body, true);
            return json_encode(['data' => $data['items'] ?? []]);
        } catch (\Exception $e) {
            return json_encode(['error' => 'API request failed']);
        }
    }

    private static function getApiKey()
    {
        $configPath = JPATH_ROOT . '/configuration/youtube.php';

        if (!file_exists($configPath)) {
            return '';
        }

        $config = include $configPath;
        return $config['api_key'] ?? '';
    }
}