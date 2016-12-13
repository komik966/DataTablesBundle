<?php
namespace DataTablesBundle\Request;

use DataTablesBundle\PopulateWithArray;

class Order extends PopulateWithArray
{
    public $column;
    public $dir;
}