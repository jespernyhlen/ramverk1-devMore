<?php

namespace Jen\Question\HTMLForm;

use Anax\HTMLForm\FormModel;
use Jen\Question\Question;
use Psr\Container\ContainerInterface;

/**
 * Searchform for questions.
 */
class SearchForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);

        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Search topic"
            ],
            [
                "topic" => [
                    "type"        => "text",
                    //"description" => "Here you can place a description.",
                    "placeholder" => "Search title, text etc..",
                    "validation" => ["not_empty"],
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Search",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }

    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit()
    {
        $topicQuery = $this->form->value("topic");
        $this->form->rememberValues();

        $this->di->get("response")->redirect("question/result/{$topicQuery}")->send();
    }
}
