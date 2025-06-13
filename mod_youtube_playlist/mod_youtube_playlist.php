<?php
/**
 * @package     YouTube Module
 * @subpackage  Modules
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Log\Log;

/**
 * Helper class para o módulo YouTube
 */
class ModYoutubeHelper
{
    /**
     * Obtém vídeos da playlist do YouTube
     */
    public static function getPlaylistVideos($params)
    {
        $playlistId = $params->get('playlist_id', '');
        $maxResults = $params->get('max_results', 10);
        $cacheTime = $params->get('cache_time', 3600);

        if (empty($playlistId)) {
            return ['error' => 'ID da playlist não configurado'];
        }

        // Verifica cache
        $cacheKey = 'mod_youtube_' . md5($playlistId . $maxResults);
        $cache = Factory::getCache('mod_youtube', '');
        $cache->setLifeTime($cacheTime);

        $videos = $cache->get($cacheKey);

        if ($videos === false) {
            // Cache expirado, busca novos dados
            $videos = self::fetchPlaylistFromYouTube($playlistId, $maxResults);

            if (!isset($videos['error'])) {
                $cache->store($videos, $cacheKey);
            }
        }

        return $videos;
    }

    private static function extractJsonBody(string $rawResponse): string
    {
        // Split on first "\r\n\r\n"
        if (preg_match('/\r\n\r\n/', $rawResponse, $matches, PREG_OFFSET_CAPTURE)) {
            return substr($rawResponse, $matches[0][1] + 4);
        }

        // Fallback: return full response if no header separator found
        return $rawResponse;
    }

    /**
     * Busca dados directamente do YouTube via plugin
     */
    private static function fetchPlaylistFromYouTube($playlistId, $maxResults)
    {
        try {
            // Carrega o plugin do sistema
            PluginHelper::importPlugin('system', 'youtube');

            // Cria instância do plugin
            $app = Factory::getApplication();
            $dispatcher = $app->getDispatcher();

            // Simula pedido interno ao plugin
            $apiKey = self::getApiKey();

            if (empty($apiKey)) {
                return ['error' => 'API Key não configurada'];
            }

            // Faz pedido directo à API do YouTube
            $url = 'https://www.googleapis.com/youtube/v3/playlistItems?' . http_build_query([
                'part' => 'snippet,contentDetails',
                'playlistId' => $playlistId,
                'maxResults' => min($maxResults, 50),
                'key' => $apiKey
            ]);

            $response = self::makeHttpRequest($url);

            if ($response === false) {
                return ['error' => 'Erro ao comunicar com YouTube API'];
            }

            // Extract only the JSON body by removing headers
            $body = self::extractJsonBody($response);

            if (empty($body)) {
                return ['error' => 'Resposta vazia ou mal formada'];
            }

            $data = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return ['error' => 'Resposta inválida da API'];
            }

            return self::processVideoData($data);

        } catch (Exception $e) {
            Log::add('YouTube Module Error: ' . $e->getMessage(), Log::ERROR, 'youtube');
            return ['error' => 'Erro interno: ' . $e->getMessage()];
        }
    }

    /**
     * Obtém a API key do ficheiro de configuração
     */
    private static function getApiKey(): string
    {
        $configPath = JPATH_ROOT . '/configuration/youtube.php';

        if (!file_exists($configPath)) {
            return '';
        }

        $config = include $configPath;
        return $config['api_key'] ?? '';
    }

    /**
     * Faz pedido HTTP
     */

    private static function makeHttpRequest(string $url): string|false
    {
        if (function_exists('curl_init')) {
            return self::makeCurlRequest($url);
        } elseif (ini_get('allow_url_fopen')) {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 30,
                    'user_agent' => 'Joomla YouTube Module/1.0',
                    'header' => "Referer: http://framemusic.org\r\n"
                ]
            ]);

            return file_get_contents($url, false, $context);
        }

        return false;
    }

    /**
     * Faz pedido usando cURL
     */
    private static function makeCurlRequest(string $url): string|false
    {
        $ch = curl_init();
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERAGENT => 'Joomla YouTube Module/1.0',
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 3,
            CURLOPT_REFERER => 'http://framemusic.org',
            CURLOPT_HEADER => true, // Include headers in response
        ];

        curl_setopt_array($ch, $options);
        curl_setopt($ch, CURLOPT_URL, $url);

        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
            print_r("<pre>cURL ERROR: " . $error . "</pre>");
        }

        // ⚠️ Important: Get info **before** closing!
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Now we can safely close the cURL handle
        curl_close($ch);

        if ($httpCode !== 200 || $response === false) {
            return false;
        }

        return $response;
    }

    /**
     * Processa dados dos vídeos
     */
    private static function processVideoData(array $data): array
    {
        $videos = [];

        if (!isset($data['items']) || !is_array($data['items'])) {
            return $videos;
        }

        foreach ($data['items'] as $item) {
            $snippet = $item['snippet'] ?? [];
            $contentDetails = $item['contentDetails'] ?? [];

            $videos[] = [
                'id' => $contentDetails['videoId'] ?? '',
                'title' => $snippet['title'] ?? '',
                'description' => $snippet['description'] ?? '',
                'thumbnail' => $snippet['thumbnails']['medium']['url'] ?? '',
                'published_at' => $snippet['publishedAt'] ?? '',
                'channel_title' => $snippet['channelTitle'] ?? ''
            ];
        }

        return $videos;
    }
}

// Obtém vídeos da playlist
$videos = ModYoutubeHelper::getPlaylistVideos($params);
$moduleId = $module->id;

// Carrega o template
require ModuleHelper::getLayoutPath('mod_youtube_playlist', $params->get('layout', 'default'));