<?php
defined('_JEXEC') or die;

if (!empty($videos)):
    ?>
    <ul class="youtube-playlist">
        <?php foreach ($videos as $video): ?>
            <?php
            $videoId = $video['snippet']['resourceId']['videoId'];
            $title = htmlspecialchars($video['snippet']['title']);
            $thumbnail = $video['snippet']['thumbnails']['default']['url'];
            ?>
            <li>
                <a href="https://www.youtube.com/watch?v= <?= $videoId ?>" target="_blank">
                    <img src="<?= $thumbnail ?>" alt="<?= $title ?>">
                    <div><?= $title ?></div>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No videos found in the playlist.</p>
<?php endif; ?>