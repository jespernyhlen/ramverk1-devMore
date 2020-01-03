<?php

namespace Jen\User;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class User extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "User";

    /**
     * Columns in the table.
     */
    public $id;
    public $username;
    public $name;
    public $presentation;
    public $email;
    public $score;
    public $password;
    public $gravatar;


    /**
     * Set the password.
     *
     * @param string $password the password to use.
     *
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verify the username and the password, if successful the object contains
     * all details from the database row.
     *
     * @param string $username  username to check.
     * @param string $password the password to use.
     *
     * @return boolean true if username and password matches, else false.
     */
    public function verifyPassword($username, $password)
    {
        $this->find("username", $username);
        return password_verify($password, $this->password);
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mp | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boole $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source https://gravatar.com/site/implement/images/php/
     */
    function setGravatar($email, $sss = 200, $ddd = 'mp', $rrr = 'g', $img = false, $atts = array())
    {
        $gravatarUrl = 'https://www.gravatar.com/avatar/';
        $gravatarUrl .= md5(strtolower(trim($email)));
        $gravatarUrl .= "?s=$sss&d=$ddd&r=$rrr";
        if ($img) {
            foreach ($atts as $key => $val) {
                $gravatarUrl .= ' ' . $key . '="' . $val . '"';
            }
        }
        $this->gravatar = $gravatarUrl;
    }

    /**
    * Update user score.
    *
    * @return 
    */
    public function updateScore($username, $value, $positive)
    {
        $this->find("username", $username);
        $this->score = ($positive > 0) ? $this->score + $value : $this->score - $value;
        $this->save();

        return true;
    }

    /**
    * Increase votes for user.
    *
    * @return 
    */
    public function incVotes($username)
    {
        $this->find("username", $username);
        $this->votes++;
        $this->save();

        return true;
    }

    /**
    * Get ranktitle depending on amount of userscore.
    *
    * @return 
    */
    public function getRankTitle($score)
    {
        if ($score <= 15) {
            $rank = "Newcomer";
        } elseif ($score <= 30) {
            $rank = "Member";
        } elseif ($score <= 50) {
            $rank = "Senior";
        } elseif ($score > 70) {
            $rank = "Highroller";
        }

        return $rank;
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

    /**
    * Get most active users.
    *
    * @param int $limit amount of users to show.
    *
    * @return User array
    */
    public function getActive($limit = 5)
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select()
                        ->from("User")
                        ->orderBy("score DESC")
                        ->limit($limit)
                        ->execute()
                        ->fetchAll();
    }

    /**
    * Get username of comment.
    *
    * @return User
    */
    public function getUser() : object
    {
        $user = new User();
        $user->setDb($this->db);
        $user->find("id", $this->username);

        return $user;
    }
}