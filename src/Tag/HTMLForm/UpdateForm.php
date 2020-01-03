<?php

// namespace Jen\User\HTMLForm;

// use Anax\HTMLForm\FormModel;
// use Psr\Container\ContainerInterface;
// use Jen\User\User;

// /**
//  * Form to update an item.
//  */
// class UpdateForm extends FormModel
// {
//     /**
//      * Constructor injects with DI container and the id to update.
//      *
//      * @param Psr\Container\ContainerInterface $di a service container
//      * @param integer             $id to update
//      */
//     public function __construct(ContainerInterface $di, $id)
//     {
//         parent::__construct($di);
//         $user = $this->getItemDetails($id);
//         $this->form->create(
//             [
//                 "id" => __CLASS__,
//                 // "legend" => "Update details of the item",
//             ],
//             [
//                 "id" => [
//                     "type" => "text",
//                     "validation" => ["not_empty"],
//                     "readonly" => true,
//                     "value" => $user->id,
//                 ],

//                 "username" => [
//                     "type" => "text",
//                     "readonly" => true,
//                     "validation" => ["not_empty"],
//                     "value" => $user->username,
//                 ],

//                 "name" => [
//                     "type" => "text",
//                     "validation" => ["not_empty"],
//                     "value" => $user->name,
//                 ],

//                 "presentation" => [
//                     "type" => "textarea",
//                     "validation" => ["not_empty"],
//                     "value" => $user->presentation,
//                 ],

//                 "submit" => [
//                     "type" => "submit",
//                     "value" => "Save",
//                     "callback" => [$this, "callbackSubmit"]
//                 ],
//             ]
//         );
//     }



//     /**
//      * Get details on item to load form with.
//      *
//      * @param integer $id get details on item with id.
//      * 
//      * @return User
//      */
//     public function getItemDetails($id) : object
//     {
//         $user = new User();
//         $user->setDb($this->di->get("dbqb"));
//         $user->find("id", $id);
//         return $user;
//     }



//     /**
//      * Callback for submit-button which should return true if it could
//      * carry out its work and false if something failed.
//      *
//      * @return bool true if okey, false if something went wrong.
//      */
//     public function callbackSubmit() : bool
//     {
//         $user = new User();
//         $user->setDb($this->di->get("dbqb"));

//         $user->find("id", $this->form->value("id"));
//         $user->name  = $this->form->value("name");
//         $user->presentation  = $this->form->value("presentation");
//         $user->save();

//         return true;
//     }



//     // /**
//     //  * Callback what to do if the form was successfully submitted, this
//     //  * happen when the submit callback method returns true. This method
//     //  * can/should be implemented by the subclass for a different behaviour.
//     //  */
//     // public function callbackSuccess()
//     // {
//     //     $this->di->get("response")->redirect("book")->send();
//     //     //$this->di->get("response")->redirect("book/update/{$book->id}");
//     // }



//     // /**
//     //  * Callback what to do if the form was unsuccessfully submitted, this
//     //  * happen when the submit callback method returns false or if validation
//     //  * fails. This method can/should be implemented by the subclass for a
//     //  * different behaviour.
//     //  */
//     // public function callbackFail()
//     // {
//     //     $this->di->get("response")->redirectSelf()->send();
//     // }
// }