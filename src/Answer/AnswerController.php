<?php

namespace Jen\Answer;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Jen\Answer\HTMLForm\CreateForm;
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

        if (!$session->get("activeUser") && !$session->get("username")) {
            return $this->di->get('response')->redirect("user/login");
        }
    }

    // /**
    //  * Create an answer.
    //  *
    //  * @return object as a response object
    //  */
    // public function createAction($postId) : object
    // {
    //     $this->checkUser();
    //     $session = $this->di->get("session");
    //     $username = $session->get("username");

    //     $page = $this->di->get("page");

    //     $form = new CreateForm($this->di, $username, $postId);
    //     $form->check();

    //     $page->add("answer/create", [
    //         "title" => "Answer this topic",
    //         "form" => $form->getHTML(),
    //     ]);

    //     return $page->render([
    //         "title" => "Answer this topic",
    //     ]);
    // }

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

        if ($this->di->get("session")->get("username") !== $answerInfo->username) {
            return $this->di->get('response')->redirect("");
        }

        $form = new UpdateForm($this->di, $id);
        $form->check();

        $page->add("answer/update", [
            "form" => $form->getHTML(),
        ]);

        $page->add("answer/goback-redirect", [
            "url" => "question/show/{$answerInfo->questionId}",
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
        $postedUsername = $request->getPost("postedUsername");
        $answer         = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answer->acceptAnswer($id);

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->updateScore($postedUsername, 7.5, 1);
        
        return $this->di->get('response')->redirect("{$returnLocation}");
    }
}
