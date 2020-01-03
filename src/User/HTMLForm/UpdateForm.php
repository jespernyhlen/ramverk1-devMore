<?php

namespace Jen\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Jen\User\User;

/**
 * Updateform for user
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

        $user = $this->getItemDetails($id);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Update user details"
            ],
            [

                "email" => [
                    "type" => "text",
                    "readonly" => true,
                    "validation" => ["not_empty"],
                    "value" => $user->email,
                ],

                "username" => [
                    "type" => "text",
                    "readonly" => true,
                    "validation" => ["not_empty"],
                    "value" => $user->username,
                ],

                "name" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                    "value" => $user->name,
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
     * @return User
     */
    public function getItemDetails($id) : object
    {
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->find("id", $id);

        return $user;
    }

    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $user = new User();
        $user->setDb($this->di->get("dbqb"));

        $user->find("id", $this->id);
        $user->name  = $this->form->value("name");
        $user->save();
        $this->form->addOutput("Changes to profile saved");

        return true;
    }
}
