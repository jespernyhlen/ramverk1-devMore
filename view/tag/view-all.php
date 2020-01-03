<?php

namespace Anax\View;

use Anax\TextFilter\TextFilter;

$filter = new TextFilter();

$tagResults = isset($tagResults) ? $tagResults : null;

?>


<?php if (!$tagResults) : ?>
    <div class="text-center">
        <p>Sorry, there were no results for tag <b>“ <?= $searchQuery ?> ”</b></p>
        <a href="<?= url("tag/search"); ?>">View all tags</a>
    </div>
    <?php
    return;
endif;
?>

<div class="view-all-questions">
<h1 class="main-title">Topics with tag including “ <b><?= $searchQuery ?></b> “</h1>
    <?php foreach ($tagResults as $result) : ?>
        <div class="view-all-question">
            <p class="question-posted-title">Posted by <a href="<?= url("user/showprofile/{$result->question->username}"); ?>"> <?= $result->question->username ?></a> <?= $result->question->created ?></p>
            <a href="<?= url("question/show/{$result->question->id}"); ?>"><div class="question-content overview">
                <h5><?= $result->question->title ?></h5>
                <?php $preview = (strlen($result->question->message) > 300 ? substr($result->question->message, 0, 300) . "..." : $result->question->message); ?>
                <p class="question-message"><?= $filter->parse($preview, ["markdown"])->text ?></p>
            </div></a>
            <a href="<?= url("question/show/{$result->question->id}"); ?>"><div class="comment-amount"><i class="fas fa-comment"></i> <?= $result->question->answersAmount ?> Answers to this topic</div></a>
            <!-- <div class="text-right"><a class="question-btn" href="<?= url("question/show/{$question->id}"); ?>"> Read</a></div> -->
        
        </div>
    <?php endforeach; ?>
</div>