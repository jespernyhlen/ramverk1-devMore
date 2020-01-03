<?php

namespace Jen\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use Jen\User\User;
use Psr\Container\ContainerInterface;

/**
 * Createform for user
 */
class CreateForm extends FormModel
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
                "legend" => "Register user",
            ],
            [
                "username" => [
                    "type"        => "text",
                    "validation" => ["not_empty"],
                    "placeholder" => "Enter username",
                ],

                "name" => [
                    "type"        => "text",
                    "validation" => ["not_empty"],
                    "placeholder" => "Enter full name",
                ],

                "email" => [
                    "type"        => "text",
                    "validation" => ["not_empty"],
                    "placeholder" => "Enter email",
                ],
        
                "password" => [
                    "type"        => "password",
                    "validation" => ["not_empty"],
                    "placeholder" => "Enter password",
                ],
        
                "repeat-password" => [
                    "type"        => "password",
                    "validation" => [
                        "match" => "password",
                        "not_empty"
                    ],
                    "placeholder" => "Repeat password",
                ],
        
                "submit" => [
                    "type" => "submit",
                    "value" => "Register",
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
        $username      = $this->form->value("username");
        $name          = $this->form->value("name");
        $email         = $this->form->value("email");
        $password      = $this->form->value("password");
        $passwordAgain = $this->form->value("repeat-password");

        if ($password !== $passwordAgain) {
            $this->form->rememberValues();
            $this->form->addOutput("Password did not match.");
            return false;
        }

        if (strlen($password) < 4) {
            $this->form->rememberValues();
            $this->form->addOutput("Password must be at least 4 characters in length.");
            return false;
        }

        $user = new User();
        $user->setDb($this->di->get("dbqb"));

        if (strlen($username) < 4) {
            $this->form->rememberValues();
            $this->form->addOutput("Username must be at least 4 characters in length.");
            return false;
        }

        $checkUsername = $user->findAllWhere("username = ?", $username);

        if (count($checkUsername) !== 0) {
            $this->form->rememberValues();
            $this->form->addOutput("Username already exists.");
            return false;
        }

        $checkEmail = $user->findAllWhere("email = ?", $email);

        if (count($checkEmail) !== 0) {
            $this->form->rememberValues();
            $this->form->addOutput("Email already exists.");
            return false;
        }

        $user->username = $username;
        $user->name = $name;
        $user->email = $email;
        $user->setGravatar($email);
        $user->setPassword($password);
        $user->score = 0;

        $user->save();
        $this->form->addOutput("User successfully registered");

        return true;
    }
}
