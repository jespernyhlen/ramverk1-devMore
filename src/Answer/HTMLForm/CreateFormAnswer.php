<?php

namespace Jen\Answer\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Jen\Answer\Answer;
use Jen\User\User;
use Jen\Tag\Tag;

/**
 * Form to create an answer.
 */
class CreateFormAnswer extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $username, $questionId)
    {
        parent::__construct($di);
        $this->username = $username;
        $this->questionId = $questionId;

        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Answer this topic",
                "escape-values" => false,
            ],
            [
                "message" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                    "placeholder" => "Write an answer for this topic (Max 2000 characters)"
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
        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answer->questionId  = $this->questionId;
        $answer->username  = $this->username;
        $answer->message = $this->form->value("message");
        $answer->points = 0;
        
        if (strlen($answer->message) > 2000) {
            $this->form->rememberValues();
            $this->form->addOutput("Message can't include more then 2000 characters.");
            return false;
        }
        $answer->save();

        $user = $answer->getUser($this->username);
        $user->answers++;
        $user->score = $user->score + 5;
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
