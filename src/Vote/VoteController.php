<?php

namespace Jen\Vote;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

use Jen\User\User;
use Jen\Question\Question;
use Jen\Answer\Answer;
use Jen\Comment\Comment;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class VoteController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Handler with form to update an item.
     *
     * @param int $id the id to update.
     *
     * @return object as a response object
     */
    public function indexActionPost() : object
    {
        // $this->checkUser();
        $session = $this->di->get("session");
        if (!$session->get("activeUser")) {
            return $this->di->response->redirect("user/login");
        }

        $request         = $this->di->get("request");
        $username        = $session->get("username");
        $id              = $request->getPost("id");
        $type            = $request->getPost("type");
        $value           = $request->getPost("vote");
        $postedUsername  = $request->getPost("postedUsername");
        $returnLocation  = $request->getPost("location");
        $vote            = new Vote();
        $vote->setDb($this->di->get("dbqb"));
        $voteSuccess = $vote->updatePoints($username, $id, $type);

        if ($voteSuccess && $postedUsername !== $session->get("username")) {
            switch ($type) {
                case "question":
                    $question = new Question();
                    $question->setDb($this->di->get("dbqb"));
                    $question->updatePoints($id, $value);

                    break;
                case "answer":
                    $answer = new Answer();
                    $answer->setDb($this->di->get("dbqb"));
                    $answer->updatePoints($id, $value);

                    break;
                case "comment":
                    $comment = new Comment();
                    $comment->setDb($this->di->get("dbqb"));
                    $comment->updatePoints($id, $value);

                    break;
                default:
                    break;
            }
            $user = new User();
            $user->setDb($this->di->get("dbqb"));
            $user->updateScore($postedUsername, 0.5, $value);
            $user->updateScore($username, 0.25, 1);
            $user->incVotes($username);
        }

        return $this->di->response->redirect("{$returnLocation}");
    }
}
