<?php

namespace Anax\View;

use Anax\TextFilter\TextFilter;

$filter = new TextFilter();

// Gather incoming variables and use default values if not set
$questions = isset($questions) ? $questions : null;


?><h5>Topics from user</h5>


<?php if (!$questions) : ?>
    <p>There are no topics from this user.</p>
    <?php
    return;
endif;
?>
<div class="view-all-questions">
    <?php foreach ($questions as $question) : ?>
        <div class="view-all-question">
            <p class="question-posted-title">Posted by <a href="<?= url("user/showprofile/{$question->username}"); ?>"> <?= $question->username ?></a> <?= $question->created ?></p>
            <a href="<?= url("question/show/{$question->id}"); ?>"><div class="question-content overview">
                <h5><?= $question->title ?></h5>
                <?php $preview = (strlen($question->message) > 100 ? substr($question->message, 0, 100) . "..." : $question->message); ?>
                <p class="question-message"><?= $filter->parse($preview, ["markdown"])->text ?></p>
            </div></a>
        </div>
    <?php endforeach; ?>
</div>