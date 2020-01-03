<?php

namespace Jen\Home;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Jen\Question\Question;
use Jen\Answer\Answer;
use Jen\Tag\Tag;
use Jen\User\User;

/**
* Home controller.
*/
class HomeController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;
    
    /**
    * Home page
    *
    * @return object as a response object
    */
    public function indexActionGet() : object
    {
        $page = $this->di->get("page");

        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $questions = $question->getQuestions(5);

        $tag = new Tag();
        $tag->setDb($this->di->get("dbqb"));
        $tags = $tag->getPopular();

        foreach ($questions as $q) {
            $answer = new Answer();
            $answer->setDb($this->di->get("dbqb"));
            $q->answersAmount = count($answer->findAllWhere("questionId = ?", $q->id));
            $q->tags = $tag->findAllWhere("questionId = ?", $q->id);
        };

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $users = $user->getActive();

        foreach ($users as $usr) {
            $rankTitle = $user->getRankTitle($usr->score);
            $usr->rankTitle = $rankTitle;
        }

        $page->add("home/view-top-questions", [
            "questions" => $questions,
        ], "main");

        $page->add("home/view-top-users", [
            "users" => $users,
        ], "sidebar-right");

        $page->add("home/view-top-tags", [
            "tags" => $tags
        ], "sidebar-right");


        return $page->render([
            "title" => "Home",
        ]);
    }
}
