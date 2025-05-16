<?php
namespace My\Module\YoutubePlaylist\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Http\HttpFactory;

class YoutubePlaylistHelper
{
    public static function getYoutubePlaylist($playlistId, $maxResults)
    {
        if (empty($playlistId)) {
            return [];
        }

        // Load API key from secure config
        $apiKey = self::getApiKey();

        $url = "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults= {$maxResults}&playlistId={$playlistId}&key={$apiKey}";

        $http = HttpFactory::getHttp();
        try {
            $response = $http->get($url);
            $data = json_decode($response->body, true);
            return $data['items'] ?? [];
        } catch (\Exception $e) {
            JFactory::getApplication()->enqueueMessage('Failed to fetch YouTube playlist.', 'error');
            return [];
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