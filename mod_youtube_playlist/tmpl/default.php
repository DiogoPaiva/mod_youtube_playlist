<?php
/**
 * @package     YouTube Module
 * @subpackage  Modules
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

$document = Factory::getDocument();

// Adiciona CSS
$document->addScript(Uri::root() . 'modules/mod_youtube_playlist/tmpl/lightbox.js');
$document->addStyleSheet(Uri::root() . 'modules/mod_youtube_playlist/tmpl/mod_youtube.css');
?>

<div id="mod-youtube-<?php echo $moduleId; ?>" class="mod-youtube-container">
    <?php if (isset($videos['error'])): ?>
        <!-- Erro -->
        <div class="mod-youtube-error">
            <?php echo htmlspecialchars($videos['error']); ?>
        </div>

    <?php elseif (empty($videos)): ?>
        <!-- Sem vídeos -->
        <div class="mod-youtube-no-videos">
            Não foram encontrados vídeos nesta playlist.
        </div>

    <?php else: ?>
        <!-- Lista de vídeos -->
        <?php foreach ($videos as $video): ?>
            <?php
            $videoUrl = 'https://www.youtube.com/watch?v=' . urlencode($video['id']);
            $publishedDate = '';

            if (!empty($video['published_at'])) {
                $date = new DateTime($video['published_at']);
                $publishedDate = $date->format('d/m/Y');
            }

            // Limita descrição se muito longa
            $description = '';
            if ($params->get('show_description', 1) && !empty($video['description'])) {
                $description = strlen($video['description']) > 200
                    ? substr($video['description'], 0, 200) . '...'
                    : $video['description'];
            }
            ?>

            <div class="mod-youtube-video">
                <?php if (!empty($video['thumbnail'])): ?>
                    <div class="mod-youtube-thumbnail">
                        <a href="https://www.youtube.com/watch?v=<?php echo urlencode($video['id']); ?>" class="lightbox-trigger">
                            <img src="<?php echo htmlspecialchars($video['thumbnail']); ?>"
                                alt="<?php echo htmlspecialchars($video['title']); ?>" loading="lazy">
                        </a>
                    </div>
                <?php endif; ?>

                <div class="mod-youtube-content">
                    <div class="mod-youtube-title">
                        <a href="https://www.youtube.com/watch?v=<?php echo urlencode($video['id']); ?>"
                            class="lightbox-trigger">
                            <?php echo htmlspecialchars($video['title']); ?>
                        </a>
                    </div>

                    <?php if (!empty($description)): ?>
                        <div class="mod-youtube-description">
                            <?php echo nl2br(htmlspecialchars($description)); ?>
                        </div>
                    <?php endif; ?>

                    <div class="mod-youtube-meta">
                        <?php if (!empty($video['channel_title'])): ?>
                            Por <?php echo htmlspecialchars($video['channel_title']); ?>
                        <?php endif; ?>

                        <?php if (!empty($publishedDate)): ?>
                            <?php if (!empty($video['channel_title'])): ?>
                                •
                            <?php endif; ?>
                            <?php echo $publishedDate; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    <?php endif; ?>
    <div id="lightbox" class="lightbox-overlay">
        <div class="lightbox-content">
            <img id="lightbox-image" src="" alt="" />
        </div>
        <span class="lightbox-close">&times;</span>
    </div>
</div>