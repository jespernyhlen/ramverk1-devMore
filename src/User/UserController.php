<?php

namespace Jen\User;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Jen\User\HTMLForm\LoginForm;
use Jen\User\HTMLForm\CreateForm;
use Jen\User\HTMLForm\UpdateForm;
use Jen\User\HTMLForm\SearchForm;
use Jen\Question\Question;
use Jen\Answer\Answer;

/**
 * User controller
 */
class UserController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Check if active user, othervise redirect to login
     *
     * @return
     */
    private function checkUser()
    {
        $session = $this->di->get("session");

        if (!$session->get("active_user") && !$session->get("username")) {
            return $this->di->response->redirect("user/login");
        }
    }

    /**
     * Redirect to search page.
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        return $this->di->response->redirect("user/search");
    }

    /**
     * Search for users.
     *
     * @return object as a response object
     */
    public function searchAction() : object
    {
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $users = $user->findAll();
        
        return $this->usersView($users);
    }

    /**
     * Show search result of users.
     *
     * @return object as a response object
     */
    public function resultAction($searchQuery) : object
    {
        $sortby = $this->di->get("request")->getGet("sortby") ?? "created DESC";

        $users = new User();
        $users->setDb($this->di->get("dbqb"));
        $users = $users->findAllWhereOrderBy("username LIKE ?", ["%$searchQuery%"], $sortby);

        return $this->usersView($users, $searchQuery);
    }

     /**
     * Create views for user page.
     *
     * @param array $users to show.
     * @param string $searchQuery user to search, optional.
     *
     * @return object as a response object
     */
    public function usersView($users, $searchQuery = null) : object
    {
        $page = $this->di->get("page");

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        
        foreach ($users as $usr) {
            $rankTitle = $user->getRankTitle($usr->score);
            $usr->rankTitle = $rankTitle;
        }

        $form = new SearchForm($this->di);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ], "sidebar-right");

        $page->add("user/view-all", [
            "users" => $users,
            "searchQuery" => $searchQuery ?? null
        ]);

        return $page->render([
            "title" => "Users",
        ]);
    }

    /**
     * Login user.
     *
     * @return object as a response object
     */
    public function loginAction() : object
    {
        $page = $this->di->get("page");
        $form = new LoginForm($this->di);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        $page->add("user/login-redirect");

        return $page->render([
            "title" => "Login",
        ]);
    }

    /**
    * Logout user.
    *
    * @return object as a response object
    */
    public function logoutAction() : object
    {
        $this->checkUser();

        $session = $this->di->get("session");
        $page = $this->di->get("page");

        $session->delete("user_id");
        $session->delete("username");
        $session->delete("active_user");

        return $this->di->response->redirect("user/login");
    }

    /**
     * Create new user.
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        $page = $this->di->get("page");
        $form = new CreateForm($this->di);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        $page->add("user/register-redirect");

        return $page->render([
            "title" => "Register",
        ]);
    }

    /**
     * Update user.
     *
     * @param int $userId the id of user to update.
     *
     * @return object as a response object
     */
    public function updateAction(int $userId) : object
    {
        $this->checkUser();
        $session = $this->di->get("session");

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $username = $user->findById($userId)->username;

        if ($username !== $session->get("username")) return $this->di->response->redirect("");

        $page = $this->di->get("page");
        $form = new UpdateForm($this->di, $userId);
        $form->check();

        $page->add("user/update", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Update profile",
        ]);
    }

    /**
     * Show logged in user.
     *
     * @return object as a response object
     */
    public function profileAction() : object
    {
        $this->checkUser();
        $page = $this->di->get("page");
        $session = $this->di->get("session");
        $username = $session->get("username");

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $userInfo = $user->findWhere("username = ?", $username);

        $page->add("user/profile", [
            "userInfo" => $userInfo,
        ]);

        return $page->render([
            "title" => "Profile " . $userInfo->username,
        ]);
    }

    /**
     * Show user profile.
     *
     * @return object as a response object
     */
    public function showprofileAction($username) : object
    {
        $page = $this->di->get("page");

        $user = new User();
        $user->setDb($this->di->get("dbqb"));

        $userInfo = $user->findWhere("username = ?", $username);

        if (!$userInfo->id) return $this->di->response->redirect("");

        $userInfo->rankTitle = $user->getRankTitle($userInfo->score);
        $question = new Question();
        $question->setDb($this->di->get("dbqb"));
        $questions = $question->findAllWhere("username = ?", $username);

        $answer = new Answer();
        $answer->setDb($this->di->get("dbqb"));
        $answers = $answer->findAllWhere("username = ?", $username);

        foreach ($answers as $answ) {
            $answerQuestion = $question->findWhere("id = ?", $answ->question_id);
            $answ->questionTitle = $answerQuestion->title;
            $answ->questionId = $answerQuestion->id;
        }

        $page->add("user/showprofile", [
            "userInfo" => $userInfo,
        ]);

        $page->add("user/profile-questions", [
            "questions" => $questions,
        ]);

        $page->add("user/profile-answers", [
            "answers" => $answers,
        ]);

        return $page->render([
            "title" => "View user " . $userInfo->username,
        ]);
    }
}
