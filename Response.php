<?php
namespace DataTablesBundle;

class Response
{
    public $draw;
    public $recordsTotal;
    public $recordsFiltered;
    public $data = array();
}