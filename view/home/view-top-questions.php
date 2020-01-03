<?php

namespace Anax\View;

use Anax\TextFilter\TextFilter;

$filter = new TextFilter();

$questions = isset($questions) ? $questions : null;
$tags = isset($tags) ? $tags : null;

?>

<?php if (!$questions) : ?>
    <p>There are no items to show.</p>
    <?php
    return;
endif;
?>

<div class="view-all-questions">
<h1 class="main-title">Most recent topics</h1>
    <?php foreach ($questions as $question) : ?>
        <div class="view-all-question">
            <div class="question-row">
                <div class="question-col-left">
                    <p class="post-score"><?= $question->points ?></p>
                </div>
                <div class="question-col-right">
                <div class="question-tags text-left">
                    <p class="question-posted-title">Posted by <a href="<?= url("user/showprofile/{$question->username}"); ?>"> <?= $question->username ?></a> <?= $question->created ?></p>
                        <?php if ($question->tags) : ?>
                                <?php foreach ($question->tags as $tag) : ?>
                                    <a class="question-tag" href="<?= url("tag/result/{$tag->tag}"); ?>">#<?= $tag->tag ?></a>
                                <?php endforeach; ?>
                            <?php
                        endif;
                        ?>
                    </div>

                    <a href="<?= url("question/show/{$question->id}"); ?>"><div class="question-content overview">
                        <h5><?= $question->title ?></h5>
                        <?php $preview = (strlen($question->message) > 300 ? substr($question->message, 0, 300) . "..." : $question->message); ?>
                        <p class="question-message"><?= $filter->parse($preview, ["markdown"])->text ?></p>
                    </div></a>
                    <a href="<?= url("question/show/{$question->id}"); ?>"><div class="comment-amount"><i class="fas fa-comment"></i> <?= $question->answersAmount ?> Answers to this topic</div></a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <p class="view-all-tag-btn">
        <a href="<?= url("question"); ?>">Go to topics</a>
    </p>
</div>