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
$questions = isset($questions) ? $questions : null;
$tags = isset($tags) ? $tags : null;


// Create urls for navigation
$urlToCreate = url("question/create");
$urlToDelete = url("question/delete");



?>



<?php if (!$questions) : ?>
    <div class="text-center">
        <p>Sorry, there were no results for post including <b>“ <?= $searchQuery ?> ”</b></p>
        <a href="<?= url("question/search"); ?>">View all topics</a>
    </div>
<?php
    return;
endif;
?>

<div class="view-all-questions">
<?php echo ($searchQuery) ? "<h1 class='main-title'>Topics including “ <b>$searchQuery</b> “</h1>" : "<h1 class='main-title'>All topics</h1>" ?>

<!-- echo empty($address['street2']) ? "Street2 is empty!" : $address['street2']; -->
    
    <div class="question-create-btn-container">
        <a href="<?= $urlToCreate ?>">Create new topic</a>
        <form method="get" class="sortby" action="#" name="myform">
            <select class="select-form" name="sortby" onchange="myform.submit();">
                <option selected="selected">SORT TOPIC BY</option>
                <option value="created DESC">New</option> 
                <option value="created ASC">Old</option> 
                <option value="points DESC">Best ranked</option> 
                <option value="points ASC">Worst ranked</option> 
            </select>
        </form>
    </div>


    <?php foreach ($questions as $question) : ?>
        <div class="view-all-question">
            <div class="question-row">
                <div class="question-col-left">
                    <form class="relevance-vote-form" name="relevance-vote" action="<?= url("vote"); ?>" method="post">
                        <input type="hidden" name="location"value="question"/>
                        <input type="hidden" name="vote"value="1"/>
                        <input type="hidden" name="type"value="question"/>
                        <input type="hidden" name="id" value="<?= $question->id ?>"/>
                        <input type="hidden" name="posted_username" value="<?= $question->username ?>"/>
                        <button class="btn-no-show"><div class="arrow-up"></div></button>
                    </form>
                    <p class="post-score"><?= $question->points ?></p>
                    <form class="relevance-vote-form" name="relevance-vote" action="<?= url("vote"); ?>" method="post">
                        <input type="hidden" name="location"value="question"/>
                        <input type="hidden" name="vote" value="-1"/>
                        <input type="hidden" name="type"value="question"/>
                        <input type="hidden" name="id" value="<?= $question->id ?>"/>
                        <input type="hidden" name="posted_username" value="<?= $question->username ?>"/>
                        <button class="btn-no-show"><div class="arrow-down"></div></button>
                    </form>
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
                    <!-- <div class="text-right"><a class="question-btn" href="<?= url("question/show/{$question->id}"); ?>"> Read</a></div> -->
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>