<?php

use Joomla\DI\Container;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\Ajax\Youtube_playlist\Extension\YoutubePlaylist;

return [
    'plugins' => [
        'ajax' => [
            'youtube_playlist' => function (Container $container) {
                $config = (array) PluginHelper::getPlugin('ajax', 'youtube_playlist');
                $dispatcher = $container->get(DispatcherInterface::class);

                $plugin = new YoutubePlaylist($dispatcher, $config);
                $plugin->setApplication(Factory::getApplication());

                // Regista o evento explicitamente
                $dispatcher->addListener('onAjaxYoutube_playlist', [$plugin, 'handleAjaxRequest']);

                return $plugin;
            }
        ]
    ]
];