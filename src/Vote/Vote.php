<?php

namespace Jen\Vote;

use Anax\DatabaseActiveRecord\ActiveRecordModel;
use Jen\User\User;
use Jen\Tag\Tag;
use Jen\Comment\Comment;




/**
 * A database driven model using the Active Record design pattern.
 */
class Vote extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Points";



    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $point_for_id;
    public $point_for_type;
    public $username;


    /**
    * Get comments from id.
    *
    * @return 
    */
    public function updatePoints($username, $point_for_id, $point_for_type)
    {
        $checkUserVoted = $this->findWhere("username = ? and point_for_id = ? and point_for_type = ?", [$username, $point_for_id, $point_for_type]);

        if ($checkUserVoted->id == null) {
            // var_dump($checkUserVoted);
            $this->username = $username;
            $this->point_for_id = $point_for_id;
            $this->point_for_type = $point_for_type;

            $this->save();

            return true;
        }
        return false;
    }
}
