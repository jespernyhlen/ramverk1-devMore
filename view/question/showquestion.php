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
$question = isset($question) ? $question : null;
$tags = isset($tags) ? $tags : null;
$answers = isset($answers) ? $answers : null;



// Create urls for navigation
$urlToCreate = url("question/create");
$urlToDelete = url("question/delete");



?>
<!-- <h1>View all items</h1> -->


<?php if (!$question) : ?>
    <p>There are no items to show.</p>
    <?php
    return;
endif;
?>

<div class="view-all-question">
    <div class="question-row">
        <div class="question-col-left">
            <form class="relevance-vote-form" name="relevance-vote" action="<?= url("vote"); ?>" method="post">
                <input type="hidden" name="location"value="question/show/<?= $question->id ?>"/>
                <input type="hidden" name="vote"value="1"/>
                <input type="hidden" name="type"value="question"/>
                <input type="hidden" name="id" value="<?= $question->id ?>"/>
                <input type="hidden" name="postedUsername" value="<?= $question->username ?>"/>
                <button class="btn-no-show"><div class="arrow-up"></div></button>
            </form>
            <p class="post-score"><?= $question->points ?></p>
            <form class="relevance-vote-form" name="relevance-vote" action="<?= url("vote"); ?>" method="post">
                <input type="hidden" name="location"value="question/show/<?= $question->id ?>"/>
                <input type="hidden" name="vote" value="-1"/>
                <input type="hidden" name="type"value="question"/>
                <input type="hidden" name="id" value="<?= $question->id ?>"/>
                <input type="hidden" name="postedUsername" value="<?= $question->username ?>"/>
                <button class="btn-no-show"><div class="arrow-down"></div></button>
            </form>
        </div>
        <div class="question-col-right">
                <div class="question-tags text-left">
                    <p class="question-posted-title">Posted by <a href="<?= url("user/showprofile/{$question->username}"); ?>"> <?= $question->username ?></a> <?= $question->created ?></p>
                    <?php if ($tags) : ?>
                        <?php foreach ($tags as $tag) : ?>
                            <a class="question-tag" href="<?= url("tag/result/{$tag->tag}"); ?>">#<?= $tag->tag ?></a>
                        <?php endforeach; ?>
                        <?php
                    endif;
                    ?>
                </div>
            
            <div class="question-content">
                <h5><?= $question->title ?> </h5>
                <p class="question-message"> <?= $filter->parse($question->message, ["markdown"])->text ?></p>
            </div>
            <div class="text-right">
                    <a href="<?= url("answer/create/{$question->id}"); ?>"> Answer</a>
                <?php if ($questionOwner) : ?>
                    | <a href="<?= url("question/update/{$question->id}"); ?>"> Edit</a>
                <?php endif; ?> 
            </div>
        </div>
    </div>
</div>
