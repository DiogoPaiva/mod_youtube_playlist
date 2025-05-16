<?php
namespace My\Module\YoutubePlaylist\Site\Module;

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

class Module
{
    public function render($module, $params)
    {
        ob_start();
        require ModuleHelper::getLayoutPath('mod_youtube_playlist');
        return ob_get_clean();
    }
}