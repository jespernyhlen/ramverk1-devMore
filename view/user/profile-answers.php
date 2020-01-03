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
            <a href="<?= url("question/show/{$answer->questionId}"); ?>"><div class="question-content overview">
                <?php $preview = (strlen($answer->message) > 110 ? substr($answer->message, 0, 110) . "..." : $answer->message); ?>
                <?= $filter->parse($preview, ["markdown"])->text ?>
            </div></a>
        </div>
    <?php endforeach; ?>
</div>