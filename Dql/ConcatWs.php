<?php
namespace DataTablesBundle\Dql;

class ConcatWs extends ColumnAbstract
{
    private $collection;

    private $alias;

    private $separator;

    public function __construct(ColumnCollection $collection, $alias, $separator)
    {
        $this->collection = $collection;
        $this->alias = $alias;
        $this->separator = $separator;
    }

    public function getNameBeforeAsStatement()
    {
        $result = "CONCAT_WS('" . $this->separator . "',";
        foreach ($this->collection as $column) {
            $result .= $column->getNameBeforeAsStatement() . ',';
        }
        $result = rtrim($result, ',');
        return $result . ')';
    }

    public function getAlias()
    {
        return $this->alias;
    }
}