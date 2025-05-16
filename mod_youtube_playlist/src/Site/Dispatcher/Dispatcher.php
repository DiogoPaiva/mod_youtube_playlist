<?php
namespace My\Module\YoutubePlaylist\Site\Dispatcher;

defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Dispatcher\DispatcherInterface;

class Dispatcher extends AbstractModuleDispatcher implements DispatcherInterface
{
    protected function getLayoutData(): array
    {
        return [
            'videos' => \My\Module\YoutubePlaylist\Site\Helper\YoutubePlaylistHelper::getYoutubePlaylist(
                $this->params->get('playlist_id'),
                $this->params->get('max_results', 5)
            )
        ];
    }
}