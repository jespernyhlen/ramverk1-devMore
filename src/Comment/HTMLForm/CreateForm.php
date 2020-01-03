<?php

namespace Jen\Comment\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Jen\Comment\Comment;
use Jen\User\User;
use Jen\Tag\Tag;

/**
 * Form to create a comment.
 */
class CreateForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $username, $values)
    {
        parent::__construct($di);
        $this->username = $username;
        $this->questionId = $values["questionId"];
        $this->answerId = $values["answerId"];

        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Comment this answer",
            ],
            [
                "message" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                    "placeholder" => "Write an comment for this answer (Max 200 characters)"
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Submit",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }

    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $comment->username  = $this->username;
        $comment->questionId  = $this->questionId;
        $comment->answerId  = $this->answerId;
        $comment->message = $this->form->value("message");

        if (strlen($comment->message) > 200) {
            $this->form->rememberValues();
            $this->form->addOutput("Comment can't include more then 200 characters.");
            return false;
        }
        $comment->save();

        $user = $comment->getUser();
        $user->comments++;
        $user->score = $user->score + 2.5;
        $user->save();

        return true;
    }

    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("question/show/{$this->questionId}")->send();
    }
}
