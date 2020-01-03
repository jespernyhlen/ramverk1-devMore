<?php

namespace Jen\Answer;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Jen\Answer\HTMLForm\CreateForm;
use Jen\Answer\HTMLForm\EditForm;
use Jen\Answer\HTMLForm\DeleteForm;
use Jen\Answer\HTMLForm\UpdateForm;
use Jen\Tag\Tag;
use Jen\User\User;


/**
 * Answer controller
 */
class AnswerController implements ContainerInjectableInterface
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

        if (!$session->get("active_user") && !$session->get("username")) {
            return $this->di->response->redirect("user/login");
        }
    }

    /**
     * Create an answer.
     *
     * @return object as a response object
     */
    public function createAction($postId) : object
    {
        $this->checkUser();
        $session = $this->di->get("session");
        $username = $session->get("username");

        $page = $this->di->get("page");

        $form = new CreateForm($this->di, $username, $postId);
        $form->check();

        $page->add("answer/create", [
            "title" => "Answer this topic",
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Answer this topic",
        ]);
    }

    /**
     * Update an answer.
     *
     * @param int $id the id to update.
     *
     * @return object as a response object
     */
    public function updateAction(int $id) : object
    {
        $page = $this->di->get("page");

        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answerInfo = $answer->findById($id);

        if ($this->di->get("session")->get("username") !== $answerInfo->username) return $this->di->response->redirect("");

        $form = new UpdateForm($this->di, $id);
        $form->check();

        $page->add("answer/update", [
            "form" => $form->getHTML(),
        ]);

        $page->add("answer/goback-redirect", [
            "url" => "question/show/{$answerInfo->question_id}",
        ]);

        return $page->render([
            "title" => "Update answer",
        ]);
    }

    /**
     * Update answer as accepted.
     *
     * @param int $id the id to update.
     *
     * @return object as a response object
     */
    public function acceptanswerActionPost() : object
    {
        $this->checkUser();
        $request        = $this->di->get("request");
        $id             = $request->getPost("id");
        $returnLocation = $request->getPost("location");
        $posted_username = $request->getPost("posted_username");
        $answer         = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answer->acceptAnswer($id);

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->updateScore($posted_username, 7.5, 1);
        
        return $this->di->response->redirect("{$returnLocation}");
    }
}



    // /**
    //  * Show all items.
    //  *
    //  * @return object as a response object
    //  */
    // public function indexActionGet() : object
    // {
    //     $page = $this->di->get("page");
        
    //     $question = new Question();
    //     $question->setDb($this->di->get("dbqb"));
    //     $questions = $question->getQuestions();

    //     $tag = new Tag();
    //     $tag->setDb($this->di->get("dbqb"));

    //     foreach ($questions as $q) {
    //         $q->tags = $tag->getTags($q->id);
    //     };

    //     $page->add("question/view-all", [
    //         "questions" => $questions,
    //     ]);

    //     return $page->render([
    //         "title" => "A collection of items",
    //     ]);
    // }


    // /**
    //  * Handler with form to delete an item.
    //  *
    //  * @return object as a response object
    //  */
    // public function deleteAction() : object
    // {
    //     $page = $this->di->get("page");
    //     $form = new DeleteForm($this->di);
    //     $form->check();

    //     $page->add("answer/delete", [
    //         "form" => $form->getHTML(),
    //     ]);

    //     return $page->render([
    //         "title" => "Delete an item",
    //     ]);
    // }



    // /**
    //  * Handler with form to update an item.
    //  *
    //  * @param int $id the id to update.
    //  *
    //  * @return object as a response object
    //  */
    // public function updateAction(int $id) : object
    // {
    //     $page = $this->di->get("page");
    //     $form = new UpdateForm($this->di, $id);
    //     $form->check();

    //     $page->add("answer/update", [
    //         "form" => $form->getHTML(),
    //     ]);

    //     return $page->render([
    //         "title" => "Update an item",
    //     ]);
    // }


    // /**
    //  * Show an asnwer.
    //  *
    //  * @param int $id the id to show.
    //  *
    //  * @return object as a response object
    //  */
    // public function showAction(int $id) : object
    // {
    //     $page = $this->di->get("page");
        
    //     $question = new Question();
    //     $question->setDb($this->di->get("dbqb"));
    //     $questions = $question->findWhere("id = ?", $id);

    //     $tag = new Tag();
    //     $tag->setDb($this->di->get("dbqb"));

    //     $questions->tags = $tag->getTags($questions->id);

    //     $page->add("answer/show", [
    //         "question" => $questions,
    //     ]);

    //     return $page->render([
    //         "title" => "A collection of items",
    //     ]);
    // }