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
    public $question_id;
    public $message;

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
    * Get user of comment.
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
    * Get answers.
    *
    * @return array
    */
    public function getAnswersByQuestion($question_id)
    {
        $answer = new Answer();
        $answer->setDb($this->db);
        $answers = $answer->findAllWhere("question_id = ?", $question_id);
        
        return $answers;
    }

    /**
    * Update points of answer.
    *
    * @return 
    */
    public function updatePoints($answer_id, $value)
    {
        $this->find("id", $answer_id);
        $this->points = $this->points + $value;
        $this->save();

        return true;
    }

    /**
    * Set answer as accepted.
    *
    * @return 
    */
    public function acceptAnswer($answer_id)
    {
        $this->find("id", $answer_id);
        $this->accepted = 1;
        $this->save();

        return true;
    }

    /**
    * Get amount of answer to question.
    *
    * @return 
    */
    public function answersAmount($questionId)
    {
        $answer = new Answer();
        $answer->setDb($this->db);
        $amount = $answer->findAllWhere("question_id = ?", $questionId);
        
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
