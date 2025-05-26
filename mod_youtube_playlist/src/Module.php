<?php

namespace ModYoutubePlaylist;

defined('_JEXEC') or die;

use Joomla\CMS\Log\Log;
use Joomla\CMS\Http\HttpFactory;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;


class Module
{
    public static function getVideos(\stdClass $module, Registry $params): array
    {
        Log::add('[mod_youtube_playlist] A obter dados do plugin via AJAX', Log::INFO, 'mod_youtube_playlist');

        $playlistId = $params->get('playlist_id');
        $maxResults = (int) $params->get('max_results', 5);

        Log::add('[mod_youtube_playlist] Playlist ID: ' . $playlistId, Log::DEBUG, 'mod_youtube_playlist');
        Log::add('[mod_youtube_playlist] Max results: ' . $maxResults, Log::DEBUG, 'mod_youtube_playlist');

        $http = HttpFactory::getHttp();

        try {
            if (!empty($playlistId)) {
                $url = 'index.php?option=com_ajax&plugin=youtube_playlist&format=json&playlistId=' . urlencode($playlistId) . '&maxResults=' . $maxResults;
                Log::add('[mod_youtube_playlist] URL da requisição: ' . $url, Log::DEBUG, 'mod_youtube_playlist');


                $response = $http->get(\JUri::root() . $url);
                var_dump($response->body); // DEBUG
                Log::add('[mod_youtube_playlist] URL do pedido: ' . \JUri::root() . $url, Log::DEBUG, 'mod_youtube_playlist');


                Log::add('[mod_youtube_playlist] Resposta bruta: ' . $response->body, Log::DEBUG, 'mod_youtube_playlist');

                $body = json_decode($response->body, true);

                if (!empty($body['success']) && !empty($body['data']['items'])) {
                    return $body['data']['items'];
                }
                /*
                                if (isset($body['data']) && is_array($body['data'])) {
                                    Log::add('[mod_youtube_playlist] Vídeos obtidos: ' . count($body['data']), Log::INFO, 'mod_youtube_playlist');
                                    return $body['data'];
                                }
                */
                Log::add('[mod_youtube_playlist] Nenhum vídeo retornado', Log::WARNING, 'mod_youtube_playlist');
            } else {
                Log::add('[mod_youtube_playlist] Não está definida nenhuma PlaylistID', Log::WARNING, 'mod_youtube_playlist');
            }
        } catch (\Throwable $e) {
            Log::add('[mod_youtube_playlist] ERRO ao obter vídeos: ' . $e->getMessage(), Log::ERROR, 'mod_youtube_playlist');
        }

        return [];
    }
}
