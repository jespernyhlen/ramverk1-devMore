<?php

namespace Jen\Comment;

use Anax\DatabaseActiveRecord\ActiveRecordModel;
use Jen\User\User;

/**
 * A database driven model using the Active Record design pattern.
 */
class Comment extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Comment";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $question_id;
    public $answer_id;
    public $message;

    /**
    * Get questions.
    *
    *
    * @return Question array
    */
    public function getComments()
    {
        $res = $this->db->connect()
                        ->select()
                        ->from("Question")
                        ->orderBy("created DESC")
                        ->execute()
                        ->fetchAll();
        return $res;
    }

    /**
    * Get username of comment.
    *
    * @return User
    */
    public function getUser()
    {
        $user = new User();
        $user->setDb($this->db);
        $user->find("username", $this->username);
        return $user;
    }

     /**
    * Get tag.
    *
    * @return Comment
    */
    public function getCommentsByQuestion($question_id)
    {
        $comment = new Comment();
        $comment->setDb($this->db);
        $comments = $comment->findAllWhere("question_id = ?", $question_id);
        
        return $comments;
    }

    /**
    * Update points.
    *
    * @return 
    */
    public function updatePoints($comment_id, $value)
    {
        $this->find("id", $comment_id);
        $this->points = $this->points + $value;
        $this->save();

        return true;
    }
}
