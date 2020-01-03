<?php

namespace Anax\View;

$tags = isset($tags) ? $tags : null;
?>


<?php if (!$tags) : ?>
    <p>There are no items to show.</p>
    <?php
    return;
endif;
?>


<div class="view-all-tags-home">
<h1 class="main-title">Most popular tags</h1>
    <ul class="view-all-tag-home">
        <?php foreach ($tags as $tag) : ?>
                <li>
                    <a href="<?= url("tag/result/{$tag->tag}"); ?>">
                        <p><?= $tag->tag ?></p>
                    </a>
                </li>
        <?php endforeach; ?>
    </ul>
    <p class="view-all-tag-btn">
        <a href="<?= url("tag"); ?>">Go to tags</a>
    </p>
</div>