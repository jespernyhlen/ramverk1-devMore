<?php

namespace Jen\Answer;

use Anax\DatabaseActiveRecord\ActiveRecordModel;
use Jen\User\User;

/**
 * A database driven model using the Active Record design pattern.
 */
class Answer extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Answer";

    /**
     * Columns in the table.
     */
    public $id;
    public $username;
    public $questionId;
    public $message;
    public $points;

    /**
    * Get answers.
    *
    * @return Answer array
    */
    public function getAnswers()
    {
        $res = $this->db->connect()
                        ->select()
                        ->from("Answer")
                        ->orderBy("created DESC")
                        ->execute()
                        ->fetchAll();
        return $res;
    }

    /**
    * Get answers.
    *
    * @return array
    */
    public function getAnswersByQuestion($questId)
    {
        $answer = new Answer();
        $answer->setDb($this->db);
        $answers = $answer->findAllWhere("questionId = ?", $questId);
        
        return $answers;
    }

    /**
    * Get user of comment.
    *
    * @return User
    */
    public function getUser($username)
    {
        $user = new User();
        $user->setDb($this->db);
        $user->find("username", $username);
        
        return $user;
    }

    /**
    * Update points of answer.
    *
    * @return
    */
    public function updatePoints($answerId, $value)
    {
        $this->find("id", $answerId);
        $this->points = $this->points + $value;
        $this->save();

        return true;
    }

    /**
    * Set answer as accepted.
    *
    * @return
    */
    public function acceptAnswer($answerId)
    {
        $this->find("id", $answerId);
        $this->accepted = 1;
        $this->save();

        return true;
    }

    /**
    * Get amount of answer to question.
    *
    * @return
    */
    public function answersAmount($questId)
    {
        $answer = new Answer();
        $answer->setDb($this->db);
        $amount = $answer->findAllWhere("questionId = ?", $questId);
        
        return count($amount);
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
