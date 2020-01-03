<?php

namespace Jen\Question\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Jen\Question\Question;
use Jen\User\User;
use Jen\Tag\Tag;

/**
 * Form to create an question.
 */
class CreateForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $username)
    {
        parent::__construct($di);
        $this->username = $username;

        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Create a new topic",
            ],
            [
                "title" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                    "placeholder" => "Title (Max 2000 characters)",
                ],
                        
                "message" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                    "placeholder" => "Write about the topic you want to discuss (Max 2000 characters)",
                ],

                "tags" => [
                    "type" => "text",
                    "placeholder" => "economy, finance, etc. (escape tag with colon)",
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
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $question->username  = $this->username;
        $question->title  = $this->form->value("title");
        $question->message = $this->form->value("message");

        if (strlen($question->title) > 100) {
            $this->form->rememberValues();
            $this->form->addOutput("Title can't include more then 100 characters.");
            return false;
        }

        if (strlen($question->message) > 1000) {
            $this->form->rememberValues();
            $this->form->addOutput("Message can't include more then 2000 characters.");
            return false;
        }

        $question->save();

        $user = $question->getUser();
        $user->posts++;
        $user->score = $user->score + 10;
        $user->save();

        $stripped = str_replace(' ', '', $this->form->value("tags"));
        $tagList = explode(",", $stripped);
        var_dump($tagList);
        foreach (array_unique($tagList) as $tagItem) {
            if (!$tagItem == "") {
                $tag = new Tag();
                $tag->setDb($this->di->get("dbqb"));
                $tag->questionId = $question->id;
                $tag->tag = $tagItem;
                $tag->save();
            }
        }

        return true;
    }


    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("question")->send();
    }
}
