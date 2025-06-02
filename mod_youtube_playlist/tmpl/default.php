<?php
/**
 * @package     YouTube Module
 * @subpackage  Modules
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

$document = Factory::getDocument();

// Adiciona CSS
$document->addStyleDeclaration('
.mod-youtube-container {
    margin: 0;
    padding: 0;
}

.mod-youtube-error {
    padding: 10px;
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    border-radius: 4px;
    margin: 10px 0;
}

.mod-youtube-video {
    display: flex;
    margin-bottom: 15px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    transition: box-shadow 0.3s ease;
}

.mod-youtube-video:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.mod-youtube-thumbnail {
    flex-shrink: 0;
    margin-right: 15px;
}

.mod-youtube-thumbnail img {
    max-width: 120px;
    height: auto;
    border-radius: 4px;
    display: block;
}

.mod-youtube-content {
    flex: 1;
}

.mod-youtube-title {
    font-weight: bold;
    margin-bottom: 8px;
    line-height: 1.3;
}

.mod-youtube-title a {
    color: #333;
    text-decoration: none;
}

.mod-youtube-title a:hover {
    color: #0066cc;
    text-decoration: underline;
}

.mod-youtube-description {
    color: #666;
    font-size: 0.9em;
    line-height: 1.4;
    margin-bottom: 8px;
}

.mod-youtube-meta {
    font-size: 0.8em;
    color: #999;
}

.mod-youtube-no-videos {
    text-align: center;
    padding: 20px;
    color: #666;
    font-style: italic;
}

@media (max-width: 768px) {
    .mod-youtube-video {
        flex-direction: column;
    }
    
    .mod-youtube-thumbnail {
        margin-right: 0;
        margin-bottom: 10px;
        text-align: center;
    }
    
    .mod-youtube-thumbnail img {
        max-width: 200px;
    }
}
');
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
                        <a href="<?php echo $videoUrl; ?>" target="_blank" rel="noopener noreferrer">
                            <img src="<?php echo htmlspecialchars($video['thumbnail']); ?>"
                                alt="<?php echo htmlspecialchars($video['title']); ?>" loading="lazy">
                        </a>
                    </div>
                <?php endif; ?>

                <div class="mod-youtube-content">
                    <div class="mod-youtube-title">
                        <a href="<?php echo $videoUrl; ?>" target="_blank" rel="noopener noreferrer">
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
</div>