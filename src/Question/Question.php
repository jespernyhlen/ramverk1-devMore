<?php

namespace Jen\Question;

use Anax\DatabaseActiveRecord\ActiveRecordModel;
use Jen\User\User;
use Jen\Tag\Tag;
use Jen\Comment\Comment;

/**
 * A database driven model using the Active Record design pattern.
 */
class Question extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Question";

    /**
     * Columns in the table.
     */
    public $id;
    public $title;
    public $message;
    public $username;

    /**
    * Get user from username.
    *
    * @return User
    */
    public function getUser()
    {
        $user = new User();
        $user->setDb($this->db);

        return $user->find("username", $this->username);
    }

    /**
    * Check if user owns question.
    *
    * @return boolean
    */
    public function ownQuestion($username) : bool
    {
        $user = new User();
        $user->setDb($this->db);
        $theUser = $user->find("username", $this->username);

        return $theUser->username == $username;
    }


    /**
    * Get tags related to question.
    *
    * @return array
    */
    public function getTags($questionId)
    {
        $tag = new Tag();
        $tag->setDb($this->db);

        return $tag->findAllWhere("questionId = ?", $questionId);
    }

    /**
    * Get comments from id.
    *
    * @return array
    */
    public function getComments()
    {
        $comment = new Comment();
        $comment->setDb($this->db);

        return $comment->findAllWhere("questionId = ?", $this->id);
    }

    /**
    * Update points for question.
    *
    * @return
    */
    public function updatePoints($questionId, $value)
    {
        $this->find("id", $questionId);
        $this->points = $this->points + $value;
        $this->save();

        return true;
    }


    /**
    * Get questions.
    *
    * @return Question array
    */
    public function getQuestions($limit = 100)
    {
        $res = $this->db->connect()
                        ->select()
                        ->from("Question")
                        ->orderBy("created DESC")
                        ->limit($limit)
                        ->execute()
                        ->fetchAll();
        return $res;
    }


    /**
     * Find and return all questions.
     *
     * @param string $order to display the results.
     *
     * @return array of object of this class
     */
    public function findAllOrderBy($order)
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from($this->tableName)
                        ->orderBy($order)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }

    /**
     * Find and return all matching the search criteria.
     *
     * @param string $where to use in where statement.
     * @param mixed  $value to use in where statement.
     * @param string $order to display the results.
     *
     * @return array of object of this class
     */
    public function findAllWhereOrderBy($where, $value, $order)
    {
        $this->checkDb();
        $params = is_array($value) ? $value : [$value];
        return $this->db->connect()
                        ->select()
                        ->from($this->tableName)
                        ->where($where)
                        ->orderBy($order)
                        ->execute($params)
                        ->fetchAllClass(get_class($this));
    }
}
