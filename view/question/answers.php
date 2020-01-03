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
$answers = isset($answers) ? $answers : null;



// Create urls for navigation
$urlToCreate = url("question/create");
$urlToDelete = url("question/delete");



?>
<!-- <h1>View all items</h1> -->

<div class="question-answers">
    <?php if (!$answers) : ?>
        <p class="no-comments">There are no answers to this topic.</p>
    <?php
        return;
    endif;
    ?>

    <form method="get" class="sortby" action="#" name="myform">
            <select class="select-form" name="sortby" onchange="myform.submit();">
            <option selected="selected">SORT ANSWERS BY</option>
            <option value="accepted DESC">Accepted</option> 
            <option value="created DESC">New</option> 
            <option value="created ASC">Old</option> 
            <option value="points DESC">Best ranked</option> 
            <option value="points ASC">Worst ranked</option> 
        </select>
    </form>

    <?php foreach ($answers as $answer) : ?>
        <div class="question-answer">
            <div class="question-row">
                <div class="question-col-left">
                    <form class="relevance-vote-form" name="relevance-vote" action="<?= url("vote"); ?>" method="post">
                        <input type="hidden" name="location"value="question/show/<?= $question->id ?>"/>
                        <input type="hidden" name="vote"value="1"/>
                        <input type="hidden" name="type"value="answer"/>
                        <input type="hidden" name="id" value="<?= $answer->id ?>"/>
                        <input type="hidden" name="posted_username" value="<?= $answer->username ?>"/>
                        <button class="btn-no-show"><div class="arrow-up"></div></button>
                    </form>
                    <p class="post-score"><?= $answer->points ?></p>
                    <form class="relevance-vote-form" name="relevance-vote" action="<?= url("vote"); ?>" method="post">
                        <input type="hidden" name="location"value="question/show/<?= $question->id ?>"/>
                        <input type="hidden" name="vote" value="-1"/>
                        <input type="hidden" name="type"value="answer"/>
                        <input type="hidden" name="id" value="<?= $answer->id ?>"/>
                        <input type="hidden" name="posted_username" value="<?= $answer->username ?>"/>
                        <button class="btn-no-show"><div class="arrow-down"></div></button>
                    </form>
                </div>
                <div class="question-col-right">
                    <div class="answer-header">
                        <p class="question-posted-title">Posted by <a href="<?= url("user/showprofile/{$answer->username}"); ?>"> <?= $answer->username ?></a> <?= $answer->created ?> 
                            <?php if ($answer->accepted) : ?> 
                                <i class="fas fa-check accepted"></i> 
                            <?php endif; ?> 
                        </p>
                        <?php if ($questionOwner && !$answer->accepted) : ?> 
                            <form name="accept-answer" action="<?= url("answer/acceptanswer"); ?>" method="post">
                                <input type="hidden" name="location"value="question/show/<?= $question->id ?>"/>
                                <input type="hidden" name="id" value="<?= $answer->id ?>"/>
                                <input type="hidden" name="posted_username" value="<?= $answer->username ?>"/>

                                <button class="btn-no-show"><i class='fas fa-check accept'></i> </button>
                            </form>
                        <?php endif; ?> 
                    </div>
                   
                    <div class="answer-content">
                        <?= $filter->parse($answer->message, ["markdown"])->text ?>
                    </div>
                      
                    <div class="text-right">
                        <a href="<?= url("comment/create?question_id={$question->id}&answer_id={$answer->id}"); ?>"> Comment</a> 
                        <?php if ($activeUser == $answer->username) : ?>
                            | <a href="<?= url("answer/update/{$answer->id}"); ?>"> Edit</a>
                        <?php endif; ?> 
                    </div>
                    <?php foreach ($answer->comment as $c2c) : ?>
                        <div class="question-answer">
                            <div class="question-row">
                                <div class="question-col-left">
                                    <form class="relevance-vote-form" name="relevance-vote" action="<?= url("vote"); ?>" method="post">
                                        <input type="hidden" name="location"value="question/show/<?= $question->id ?>"/>
                                        <input type="hidden" name="vote"value="1"/>
                                        <input type="hidden" name="type"value="comment"/>
                                        <input type="hidden" name="id" value="<?= $c2c->id ?>"/>
                                        <input type="hidden" name="posted_username" value="<?= $c2c->username ?>"/>
                                        <button class="btn-no-show"><div class="arrow-up"></div></button>
                                    </form>
                                    <p class="post-score"><?= $c2c->points ?></p>
                                    <form class="relevance-vote-form" name="relevance-vote" action="<?= url("vote"); ?>" method="post">
                                        <input type="hidden" name="location"value="question/show/<?= $question->id ?>"/>
                                        <input type="hidden" name="vote" value="-1"/>
                                        <input type="hidden" name="type"value="comment"/>
                                        <input type="hidden" name="id" value="<?= $c2c->id ?>"/>
                                        <input type="hidden" name="posted_username" value="<?= $c2c->username ?>"/>
                                        <button class="btn-no-show"><div class="arrow-down"></div></button>
                                    </form>
                                </div>
                                <div class="question-col-right">
                                    <p class="question-posted-title">Posted by <a href="<?= url("user/showprofile/{$c2c->username}"); ?>"> <?= $c2c->username ?></a> <?= $c2c->created ?></p>
                                    <div class="answer-content">
                                        <?= $filter->parse($c2c->message, ["markdown"])->text ?>
                                    </div>
                                    <div class="text-right">
                                        <?php if ($activeUser == $c2c->username) : ?>
                                            <a href="<?= url("comment/update/{$c2c->id}"); ?>"> Edit</a>
                                        <?php endif; ?> 
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
