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
    public $pointForId;
    public $pointForType;
    public $username;

    /**
    * Get comments from id.
    *
    * @return
    */
    public function updatePoints($username, $pointsForId, $pointsForType)
    {
        $checkUserVoted = $this->findWhere("username = ? and pointForId = ? and pointForType = ?", [$username, $pointsForId, $pointsForType]);

        if ($checkUserVoted->id == null) {
            // var_dump($checkUserVoted);
            $this->username = $username;
            $this->pointForId = $pointsForId;
            $this->pointForType = $pointsForType;

            $this->save();

            return true;
        }
        return false;
    }
}
