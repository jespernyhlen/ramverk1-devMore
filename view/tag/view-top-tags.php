<?php

namespace Anax\View;

use Anax\TextFilter\TextFilter;

$filter = new TextFilter();

/**
 * View to display all books.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$tags = isset($tags) ? $tags : null;

?>


<?php if (!$tags) : ?>
    <p>There are no items to show.</p>
<?php
    return;
endif;
?>


<div class="view-all-tags">
    <h1 class="main-title">All tags related to topics</h1>
    <ul class="view-all-tag">
        <?php foreach ($tags as $tag) : ?>
            <li>
                <a href="<?= url("tag/result/{$tag->tag}"); ?>">
                    <p>#<?= $tag->tag ?></p>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>