<?php
namespace DataTablesBundle\Request;

use DataTablesBundle\PopulateWithArray;

class Request extends PopulateWithArray
{
    public $draw = 1;
    /**
     * @var Column[]
     */
    public $columns = array();
    /**
     * @var Order[]
     */
    public $order = array();
    public $start = 0;
    public $length = 10;
    /**
     * @var Search
     */
    public $search;

    public function __construct(array $propertyNameToValueArray)
    {
        $this->propertyNameToClassName = array(
            'columns' => Column::class,
            'order' => Order::class,
            'search' => Search::class
        );
        parent::__construct($propertyNameToValueArray);
    }
}