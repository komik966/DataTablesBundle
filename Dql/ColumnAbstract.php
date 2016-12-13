<?php
namespace DataTablesBundle\Dql;


abstract class ColumnAbstract implements ColumnInterface
{
    const LIKE_TYPE_LEFT = 0;
    const LIKE_TYPE_RIGHT = 1;
    const LIKE_TYPE_DOUBLE = 2;

    private $filterable = false;
    private $likeType;


    public function isFilterable()
    {
        return $this->filterable;
    }

    public function setFilterable($bool)
    {
        $this->filterable = (bool)$bool;
    }

    public function setLikeType($likeType)
    {
        if (!in_array($likeType, array(self::LIKE_TYPE_LEFT, self::LIKE_TYPE_RIGHT, self::LIKE_TYPE_DOUBLE))) {
            throw new \InvalidArgumentException('Invalid like type');
        }
        $this->likeType = $likeType;
    }

    public function getLikeType()
    {
        if (!$this->likeType) {
            $this->setLikeType(self::LIKE_TYPE_DOUBLE);
        }
        return $this->likeType;
    }
}