<?php
namespace DataTablesBundle\Request;

use DataTablesBundle\PopulateWithArray;

class Search extends PopulateWithArray
{
    public $value;
    public $regex;
}