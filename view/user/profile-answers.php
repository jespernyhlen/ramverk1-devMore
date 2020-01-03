<?php

namespace Anax\View;

use Anax\TextFilter\TextFilter;

$filter = new TextFilter();

// Gather incoming variables and use default values if not set
$answers = isset($answers) ? $answers : null;


?><h5>Answers from user</h5>


<?php if (!$answers) : ?>
    <p>There are no answers from this user.</p>
<?php
    return;
endif;
?>
<div class="view-all-questions">
    <?php foreach ($answers as $answer) : ?>
        <div class="view-all-question">
            <p class="question-posted-title">Answer by <a href="<?= url("user/showprofile/{$answer->username}"); ?>"> <?= $answer->username ?></a> <?= $answer->created ?> on topic: <b><a href="<?= url("question/show/{$answer->questionId}"); ?>"> <?= $answer->questionTitle ?></a></b></p>
            <a href="<?= url("question/show/{$answer->question_id}"); ?>"><div class="question-content overview">
                <!-- <h4><?= $question->title ?></h4> -->
                <?php $preview = (strlen($answer->message) > 110 ? substr($answer->message, 0, 110) . "..." : $answer->message); ?>
                <?= $filter->parse($preview, ["markdown"])->text ?>
            </div></a>
            <!-- <a href="<?= url("question/show/{$answer->question_id}"); ?>"><div class="comment-amount"><i class="fas fa-comment"></i> <?= $answer->answersAmount ?> Answers to this topic</div></a> -->
            <!-- <div class="text-right"><a class="question-btn" href="<?= url("question/show/{$answer->question_id}"); ?>"> Read</a></div> -->
        
        </div>
    <?php endforeach; ?>
</div>