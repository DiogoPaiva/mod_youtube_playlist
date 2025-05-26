<?php

namespace Joomla\Plugin\Ajax\Youtube_playlist\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Http\HttpFactory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\Log\Log;

final class YoutubePlaylist extends CMSPlugin
{
    protected $autoloadLanguage = true;

    public function __construct($subject, $config)
    {
        parent::__construct($subject, $config);
        Log::add('[plg_ajax_youtube_playlist] Plugin initialized', Log::INFO, 'youtube_playlist');
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'onAjaxYoutube_playlist' => 'handleAjaxRequest',
        ];
    }

    public function handleAjaxRequest(): void
    {
        // Verifica se a categoria existe
        if (!Log::existsLoggerCategory('youtube_playlist')) {
            Log::addLogger(
                [
                    'text_file' => 'plg_ajax_youtube_playlist.log',
                    'extension' => 'plg_ajax_youtube_playlist',
                    'text_file_no_php' => false,
                ],
                Log::ALL,
                ['youtube_playlist']
            );
        }

        $input = Factory::getApplication()->getInput();
        $playlistId = $input->getString('playlistId', '');
        $maxResults = $input->getInt('maxResults', 5);

        Log::add("[plg_ajax_youtube_playlist] Playlist ID: {$playlistId}", Log::DEBUG, 'youtube_playlist');
        Log::add("[plg_ajax_youtube_playlist] Max Results: {$maxResults}", Log::DEBUG, 'youtube_playlist');

        if (empty($playlistId)) {
            Log::add("[plg_ajax_youtube_playlist] Erro: playlistId em falta", Log::ERROR, 'youtube_playlist');
            echo new JsonResponse(null, 'Missing playlist ID', true);
            return;
        }

        // Caminho do ficheiro de configuração
        $configFile = JPATH_ROOT . '/configuration/youtube.php';

        if (!is_file($configFile)) {
            Log::add("[plg_ajax_youtube_playlist] Erro: Ficheiro de configuração não encontrado", Log::ERROR, 'youtube_playlist');
            echo new JsonResponse(null, 'Configuration file not found', true);
            return;
        }

        $config = include $configFile;

        if (!isset($config['api_key']) || empty($config['api_key'])) {
            Log::add("[plg_ajax_youtube_playlist] Erro: API key não definida", Log::ERROR, 'youtube_playlist');
            echo new JsonResponse(null, 'API key not configured', true);
            return;
        }

        $apiKey = $config['api_key'];

        $apiUrl = "https://www.googleapis.com/youtube/v3/playlistItems? " . http_build_query([
            'part' => 'snippet',
            'maxResults' => $maxResults,
            'playlistId' => $playlistId,
            'key' => $apiKey,
        ]);

        Log::add("[plg_ajax_youtube_playlist] Chamada à API: {$apiUrl}", Log::DEBUG, 'youtube_playlist');

        try {
            $http = HttpFactory::getHttp();
            $response = $http->get($apiUrl);
            $body = $response->body;

            Log::add("[plg_ajax_youtube_playlist] Resposta bruta da API: {$body}", Log::DEBUG, 'youtube_playlist');

            $data = json_decode($body, true);

            if (isset($data['error'])) {
                Log::add("[plg_ajax_youtube_playlist] Erro na API: {$data['error']['message']}", Log::ERROR, 'youtube_playlist');
                echo new JsonResponse(null, $data['error']['message'], true);
                return;
            }

            if (!isset($data['items'])) {
                Log::add("[plg_ajax_youtube_playlist] Nenhum item recebido", Log::WARNING, 'youtube_playlist');
                echo new JsonResponse([], null, false);
                return;
            }

            // Devolve apenas os itens da playlist
            echo new JsonResponse($data['items']);
        } catch (\Throwable $e) {
            Log::add("[plg_ajax_youtube_playlist] Exceção capturada: {$e->getMessage()}", Log::ERROR, 'youtube_playlist');
            echo new JsonResponse(null, $e->getMessage(), true);
        }
    }
}