<?php

namespace Jen\Tag;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class Tag extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Tag";



    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $tag;
    public $questionId;

    
    /**
    * Get tag.
    *
    * @return Tag
    */
    public function convertTags($questId)
    {
        $tag = new Tag();
        $tag->setDb($this->db);
        $tagString = $tag->findAllWhere("questionId = ?", $questId)[0]->tag;
        $tagList = explode(", ", $tagString);
        
        return $tagList;
    }

    /**
    * Get most popular tags.
    *
    * @param int $limit amount of tags to show
    *
    * @return Tag array
    */
    public function getPopular($limit = 5)
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select("COUNT(tag) AS tagamount, tag, questionId")
                        ->from("Tag")
                        ->groupby("tag")
                        ->orderBy("tagamount DESC")
                        ->limit($limit)
                        ->execute()
                        ->fetchAll();
    }
}
