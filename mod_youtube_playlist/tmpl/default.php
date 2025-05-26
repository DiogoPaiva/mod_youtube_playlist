<?php

defined('_JEXEC') or die;

/** @var Joomla\Registry\Registry $params */
/** @var stdClass $module */
/** @var array $videos */

use Joomla\CMS\Language\Text;

$params = $displayData['params'] ?? null;
$videos = $displayData['videos'] ?? [];
$module = $displayData['module'] ?? null;

if (!$params) {
    throw new RuntimeException('Parâmetros do módulo não definidos.');
}

// Agora podes usar $params->get()
$header = $params->get('header', 'Últimos Vídeos');

?>

<div class="mod_youtube_playlist">
    <h3><?php echo htmlspecialchars($header); ?></h3>

    <?php if (!empty($videos)): ?>
        <ul class="youtube-video-list">
            <?php foreach ($videos as $video): ?>
                <li class="youtube-video-item">
                    <a href="https://www.youtube.com/watch?v=<?php echo htmlspecialchars($video['videoId']); ?>" target="_blank"
                        rel="noopener">
                        <img src="<?php echo htmlspecialchars($video['thumbnail']); ?>"
                            alt="<?php echo htmlspecialchars($video['title']); ?>">
                        <p><?php echo htmlspecialchars($video['title']); ?></p>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p><?php echo Text::_('MOD_YOUTUBE_PLAYLIST_NO_VIDEOS'); ?></p>
    <?php endif; ?>
</div>