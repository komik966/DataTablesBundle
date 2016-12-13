<?php
namespace DataTablesBundle\Request;

use DataTablesBundle\PopulateWithArray;

class Column extends PopulateWithArray
{
    public $data;
    public $name;
    public $searchable;
    public $orderable;
    /**
     * @var Search
     */
    public $search;

    public function __construct(array $propertyNameToValueArray)
    {
        $this->propertyNameToClassName = array(
            'search' => Search::class
        );
        parent::__construct($propertyNameToValueArray);
    }

}