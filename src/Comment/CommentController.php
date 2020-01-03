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

        if (!$session->get("active_user") && !$session->get("username")) {
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
            "question_id" => $this->di->request->getGet("question_id") ?? null,
            "answer_id" => $this->di->request->getGet("answer_id") ?? null
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

        if (!$commentInfo->id || $this->di->get("session")->get("username") !== $commentInfo->username) return $this->di->response->redirect("");

        $form = new UpdateForm($this->di, $id);
        $form->check();

        $page->add("comment/update", [
            "form" => $form->getHTML(),
        ]);

        $page->add("comment/goback-redirect", [
            "url" => "question/show/{$commentInfo->question_id}",
        ]);

        return $page->render([
            "title" => "Update a comment",
        ]);
    }
}

    // /**
    //  * Handler with form to show an item.
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

    //     $page->add("question/show", [
    //         "question" => $questions,
    //     ]);

    //     return $page->render([
    //         "title" => "A collection of items",
    //     ]);
    // }

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
