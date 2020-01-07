<?php

namespace Jen\Question;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Jen\Question\HTMLForm\CreateForm;
use Jen\Question\HTMLForm\UpdateForm;
use Jen\Question\HTMLForm\SearchForm;
use Jen\Answer\HTMLForm\CreateFormAnswer;
use Jen\Tag\Tag;
use Jen\User\User;
use Jen\Answer\Answer;
use Jen\Comment\Comment;
use Jen\Vote;

/**
 * Question controller
 */
class QuestionController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Check if active user, othervise redirect to login
     *
     * @return
     */
    private function checkUser()
    {
        $session = $this->di->get("session");

        if (!$session->get("activeUser") && !$session->get("username")) {
            return $this->di->response->redirect("user/login");
        }
    }

    /**
     * Redirect to search page.
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        return $this->di->response->redirect("question/search");
    }

    /**
     * Create new question.
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        $this->checkUser();
        $page       = $this->di->get("page");
        $session    = $this->di->get("session");
        $username   = $session->get("username");
        $form       = new CreateForm($this->di, $username);
        $form->check();

        $page->add("question/create", [
            "title" => "Create a new topic",
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Create a topic",
        ]);
    }

    /**
     * Update question.
     *
     * @param int $id of question to update.
     *
     * @return object as a response object
     */
    public function updateAction(int $id) : object
    {
        $page = $this->di->get("page");

        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $questionInfo = $question->findById($id);

        if (!$questionInfo || $this->di->get("session")->get("username") !== $questionInfo->username) {
            return $this->di->response->redirect("question");
        }

        $form = new UpdateForm($this->di, $id);
        $form->check();

        $page->add("question/update", [
            "form" => $form->getHTML(),
        ]);

        $page->add("question/goback-redirect", [
            "url" => "question/show/{$id}",
        ]);

        return $page->render([
            "title" => "Update topic",
        ]);
    }

     /**
     * Show all items.
     *
     * @return object as a response object
     */
    public function searchAction() : object
    {
        $sortby = $this->di->get("request")->getGet("sortby") ?? "created DESC";
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $questions = $question->findAllOrderBy($sortby);
        
        return $this->questionView($questions);
    }

     /**
     * Show search result of questions.
     *
     * @return object as a response object
     */
    public function resultAction($searchQuery)
    {
        $sortby = $this->di->get("request")->getGet("sortby") ?? "created DESC";
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $questions = $question->findAllWhereOrderBy("title LIKE ? or message LIKE ?", ["%$searchQuery%", "%$searchQuery%"], $sortby);

        return $this->questionView($questions, $searchQuery);
    }

    /**
     * Show question.
     *
     * @param int $id the id to show.
     *
     * @return object as a response object
     */
    public function showAction(int $id) : object
    {
        $sortby = $this->di->get("request")->getGet("sortby") ?? "accepted DESC";
        $page = $this->di->get("page");
        
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $questionInfo = $question->findById($id);

        if (!$questionInfo->id) {
            return $this->di->response->redirect("");
        }

        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answers = $answer->findAllWhereOrderBy("questionId = ?", $id, $sortby);

        foreach ($answers as $answ) {
            $user = new User();
            $user->setDb($this->di->get("dbqb"));
            $comment = new Comment();
            $comment->setDb($this->di->get("dbqb"));
            $answ->comment = $comment->findAllWhere("questionId = ? and answerId = ?", [$id, $answ->id]);
        }
        $tag = new Tag();
        $tag->setDb($this->di->get("dbqb"));
        $tags = $tag->findAllWhere("questionId = ?", $id);

        $contentInfo = [
            "questionOwner" => $this->di->get("session")->get("username") == $questionInfo->username,
            "activeUser" => $this->di->get("session")->get("username"),
            "question" => $questionInfo,
            "answers" => $answers,
            "tags" => $tags
        ];

        $page->add("question/showquestion", $contentInfo);
        $form = new CreateFormAnswer($this->di, $this->di->get("session")->get("username"), $id);
        $form->check();

        $page->add("answer/create", [
            "title" => "Answer this topic",
            "form" => $this->di->get("session")->get("username") ? $form->getHTML() : null,
        ]);

        $page->add("question/answers", $contentInfo);

        return $page->render([
            "title" => $questionInfo->title,
        ]);
    }

    /**
     * Create views for question page.
     *
     * @param int $id the id to show.
     *
     * @return object as a response object
     */
    public function questionView($questions, $searchQuery = null) : object
    {
        $page = $this->di->get("page");
     
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));

        $tag = new Tag();
        $tag->setDb($this->di->get("dbqb"));

        foreach ($questions as $q) {
            $answer = new Answer();
            $answer->setDb($this->di->get("dbqb"));
            $q->answersAmount = count($answer->findAllWhere("questionId = ?", $q->id));
            $q->tags = $tag->findAllWhere("questionId = ?", $q->id);
        };

        $form = new SearchForm($this->di);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ], "sidebar-right");

        $page->add("question/view-all", [
            "questions" => $questions,
            "searchQuery" => $searchQuery ?? null
        ]);

        return $page->render([
            "title" => "Topics",
        ]);
    }
}
