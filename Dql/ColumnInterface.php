<?php
namespace DataTablesBundle\Dql;


interface ColumnInterface
{
    public function getNameBeforeAsStatement();

    public function getAlias();

    public function setFilterable($bool);

    public function isFilterable();

    public function setLikeType($likeType);

    public function getLikeType();
}