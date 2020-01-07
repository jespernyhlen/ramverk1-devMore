<?php

namespace Jen\Question\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Jen\Question\Question;
use Jen\Tag\Tag;

/**
 * Form to update a question.
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

        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $question->find("id", $id);
        $tags = $question->getTags($id);
        $tagString = "";

        if ($tags) {
            foreach ($tags as $tagItem) {
                $tagString = $tagString . $tagItem->tag . ", ";
            }
            $tagString = substr($tagString, 0, -2);
        }
                
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Update topic",
                "escape-values" => false,
            ],
            [
                "title" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                    "placeholder" => "Title (Max 100 characters)",
                    "value" => html_entity_decode($question->title)
                ],
                        
                "message" => [
                    "type" => "textarea",
                    "validation" => ["not_empty"],
                    "placeholder" => "Write about the topic you want to discuss (Max 2000 characters)",
                    "value" => html_entity_decode($question->message)
                ],

                "tags" => [
                    "type" => "text",
                    // "validation" => ["not_empty"],
                    "placeholder" => "economy, finance, etc.",
                    "value" => html_entity_decode($tagString)
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
     * @return Question
     */
    public function getItemDetails($id) : object
    {
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $question->find("id", $id);

        return $question;
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
        $question->find("id", $this->id);

        $question->username = $question->username;
        $question->title = $this->form->value("title");
        $question->message = $this->form->value("message");
        if (strlen($question->title) > 100) {
            $this->form->rememberValues();
            $this->form->addOutput("Title can't include more then 100 characters.");
            return false;
        }

        if (strlen($question->message) > 2000) {
            $this->form->rememberValues();
            $this->form->addOutput("Message can't include more then 2000 characters.");
            return false;
        }
        $question->save();

        $newTags = $this->di->request->getPost("tags");
        $newStripped = str_replace(' ', '', $newTags);
        $newTagList = explode(",", $newStripped);
        $tagsFromId = $question->getTags($this->id);

        foreach ($tagsFromId as $tagItem) {
                $removeTag = new Tag();
                $removeTag->setDb($this->di->get("dbqb"));
                $removeTag->findWhere("questionId = ? and tag = ?", [$question->id, $tagItem->tag]);
                $removeTag->delete();
        }

        foreach (array_unique($newTagList) as $tagItem) {
            if ($tagItem !== "") {
                $newTag = new Tag();
                $newTag->setDb($this->di->get("dbqb"));
                $newTag->questionId = $question->id;
                $newTag->tag = $tagItem;
                $newTag->save();
            }
        }
        $this->form->addOutput("Changes to topic saved");

        return true;
    }
}
