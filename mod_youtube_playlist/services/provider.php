<?php

defined('_JEXEC') or die;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use ModYoutubePlaylist\Module;

return new class implements ServiceProviderInterface {
    public function register(Container $container)
    {
        $container->set(Module::class, function () {
            return new Module();
        });
    }
};
