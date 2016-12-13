<?php
namespace DataTablesBundle\Dql;

class Column extends ColumnAbstract
{
    private $name;
    /**
     * http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/dql-doctrine-query-language.html#dql-select-clause
     * identification variable / alias that refers to the entity class
     * @var $entityAlias
     */
    private $entityAlias;

    /**
     * @param $name
     * @param $entityAlias
     */
    public function __construct($name, $entityAlias)
    {
        $this->name = $name;
        $this->entityAlias = $entityAlias;
    }

    public function getNameBeforeAsStatement()
    {
        return $this->entityAlias . '.' . $this->name;
    }

    public function getAlias()
    {
        return $this->entityAlias . '_' . $this->name;
    }
}