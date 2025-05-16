<?php
namespace My\Module\YoutubePlaylist\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Http\HttpFactory;

class YoutubePlaylistHelper
{
    public static function getYoutubePlaylist($playlistId, $maxResults)
    {
        if (empty($playlistId)) {
            return [];
        }

        $url = JUri::root() . 'index.php?option=com_ajax&plugin=youtube_playlist&format=json';

        $data = [
            'playlist_id' => $playlistId,
            'max_results' => $maxResults,
        ];

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($data),
            ],
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            JFactory::getApplication()->enqueueMessage('Failed to fetch YouTube playlist.', 'error');
            return [];
        }

        $json = json_decode($response, true);

        return $json['data'] ?? [];
    }
}