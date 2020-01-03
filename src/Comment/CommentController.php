<?php

namespace Jen\Comment;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Jen\Comment\HTMLForm\CreateForm;
use Jen\Comment\HTMLForm\EditForm;
use Jen\Comment\HTMLForm\DeleteForm;
use Jen\Comment\HTMLForm\UpdateForm;
use Jen\Tag\Tag;

/**
 * Comment controller
 */
class CommentController implements ContainerInjectableInterface
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
     * Create new comment.
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        $this->checkUser();
        $page = $this->di->get("page");
        $session = $this->di->get("session");
        $username = $session->get("username");

        $values = [
            "questionId" => $this->di->request->getGet("questionId") ?? null,
            "answerId" => $this->di->request->getGet("answerId") ?? null
        ];

        $form = new CreateForm($this->di, $username, $values);
        $form->check();

        $page->add("Comment/create", [
            "title" => "Comment this answer",
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Create a comment",
        ]);
    }

    /**
     * Update comment.
     *
     * @param int $id of comment to update.
     *
     * @return object as a response object
     */
    public function updateAction(int $id) : object
    {
        $page = $this->di->get("page");

        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $commentInfo = $comment->findById($id);

        if (!$commentInfo->id || $this->di->get("session")->get("username") !== $commentInfo->username) {
            return $this->di->response->redirect("");
        }

        $form = new UpdateForm($this->di, $id);
        $form->check();

        $page->add("comment/update", [
            "form" => $form->getHTML(),
        ]);

        $page->add("comment/goback-redirect", [
            "url" => "question/show/{$commentInfo->questionId}",
        ]);

        return $page->render([
            "title" => "Update a comment",
        ]);
    }
}
