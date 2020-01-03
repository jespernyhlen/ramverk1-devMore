<?php

namespace Jen\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use Jen\User\User;
use Psr\Container\ContainerInterface;

/**
 * Loginform for user
 */
class LoginForm extends FormModel
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
                "legend" => "Login user"
            ],
            [
                "username" => [
                    "type"        => "text",
                    //"description" => "Here you can place a description.",
                    "placeholder" => "Enter username",
                    "validation" => ["not_empty"],

                ],
                        
                "password" => [
                    "type"        => "password",
                    //"description" => "Here you can place a description.",
                    "placeholder" => "Enter password",
                    "validation" => ["not_empty"],

                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Login",
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
        $username = $this->form->value("username");
        $password = $this->form->value("password");

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $res = $user->verifyPassword($username, $password);

        if (!$res) {
            $this->form->rememberValues();
            $this->form->addOutput("Username or password did not match.");
            return false;
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
        $session = $this->di->get("session");
        $session->set("user_id", $this->form->value("id"));
        $session->set("username", $this->form->value("username"));
        $session->set("active_user", true);

        $this->di->get("response")->redirect("user/profile")->send();
    }
}
