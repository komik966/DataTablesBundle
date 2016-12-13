<?php
namespace DataTablesBundle\Dql;


class ColumnCollection implements \Iterator
{
    /**
     * @var ColumnInterface[]
     */
    private $columns = array();
    private $position = 0;

    public function addColumn(ColumnInterface $column)
    {
        $this->columns[] = $column;
    }

    public function getColumnByIndex($i)
    {
        return $this->columns[$i];
    }

    /**
     * @return ColumnInterface
     */
    public function current()
    {
        return $this->columns[$this->position];
    }

    public function next()
    {
        ++$this->position;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return isset($this->columns[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }
}