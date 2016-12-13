<?php
namespace DataTablesBundle\Dql;

class Conditional extends ColumnAbstract
{
    private $condition;

    private $valWhenTrue;

    private $valWhenFalse;

    private $alias;

    public function __construct($condition, $valWhenTrue, $valWhenFalse, $alias)
    {
        if (!is_string($valWhenTrue) || !is_string($valWhenFalse)) {
            throw new \InvalidArgumentException('Value when true and value when false must be strings');
        }
        $this->condition = $condition;
        $this->valWhenTrue = $valWhenTrue;
        $this->valWhenFalse = $valWhenFalse;
        $this->alias = $alias;
    }


    public function getNameBeforeAsStatement()
    {
        $result = "IF(" . $this->condition . ",'" . $this->valWhenTrue . "','" . $this->valWhenFalse . "')";
        return $result;
    }

    public function getAlias()
    {
        return $this->alias;
    }
}