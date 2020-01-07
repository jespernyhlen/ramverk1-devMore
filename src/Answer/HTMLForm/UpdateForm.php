<?php

namespace Jen\Answer\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Jen\Answer\Answer;

/**
 * Form to update an answer.
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

        $answer = $this->getItemDetails($id);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Update answer",
                "escape-values" => false,
            ],
            [
                "message" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                    "value" => $answer->message,
                    "placeholder" => "Write an answer for this topic (Max 2000 characters)"
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
     * @return Answer
     */
    public function getItemDetails($id) : object
    {
        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answer->find("id", $id);

        return $answer;
    }

    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answer->find("id", $this->id);
        $answer->message = $this->form->value("message");
        
        if (strlen($answer->message) > 2000) {
            $this->form->rememberValues();
            $this->form->addOutput("Message can't include more then 2000 characters.");
            return false;
        }
        $answer->save();
        $this->form->addOutput("Changes to answer saved");
        
        return true;
    }
}
