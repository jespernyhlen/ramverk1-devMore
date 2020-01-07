<?php

namespace Jen\Comment\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Jen\Comment\Comment;

/**
 * Form to update a comment.
 */
class UpdateForm extends FormModel
{
    /**
     * Constructor injects with DI container and the id to update.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     * @param integer             $id to update
     */
    public function __construct(ContainerInterface $di, $id)
    {
        parent::__construct($di);
        $this->id = $id;

        $comment = $this->getItemDetails($id);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Update comment",
                "escape-values" => false,
            ],
            [
                "message" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                    "value" => $comment->message,
                    "placeholder" => "Write an comment for this answer (Max 200 characters)"
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Save",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }

    /**
     * Get details on item to load form with.
     *
     * @param integer $id get details on item with id.
     *
     * @return Comment
     */
    public function getItemDetails($id) : object
    {
        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $comment->find("id", $id);

        return $comment;
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
        $comment->find("id", $this->id);
        $comment->message = $this->form->value("message");
        
        if (strlen($comment->message) > 200) {
            $this->form->rememberValues();
            $this->form->addOutput("Comment can't include more then 200 characters.");
            return false;
        }
        $comment->save();
        $this->form->addOutput("Changes to comment saved");
        
        return true;
    }
}
