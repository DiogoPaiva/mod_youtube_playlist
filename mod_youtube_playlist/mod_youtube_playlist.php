<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Layout\FileLayout;
use Joomla\Registry\Registry;
use Joomla\CMS\Log\Log;

// Carrega a classe Module
JLoader::registerNamespace('ModYoutubePlaylist', __DIR__ . '/src', false, false);

use ModYoutubePlaylist\Module;

$app = Factory::getApplication();
$module = ModuleHelper::getModule('mod_youtube_playlist');
$params = new Registry($module->params);

Log::add('[mod_youtube_playlist] A carregar módulo', Log::INFO, 'mod_youtube_playlist');

try {
    $videos = Module::getVideos($module, $params);
    Log::add('[mod_youtube_playlist] Vídeos carregados: ' . count($videos), Log::INFO, 'mod_youtube_playlist');
} catch (Throwable $e) {
    Log::add('[mod_youtube_playlist] ERRO ao obter vídeos: ' . $e->getMessage(), Log::ERROR, 'mod_youtube_playlist');
    $videos = [];
}

$layout = new FileLayout('default', __DIR__ . '/tmpl');
echo $layout->render([
    'params' => $params,
    'module' => $module,
    'videos' => $videos,
]);
