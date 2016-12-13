DataTablesBundle v0.0.1
================
Note: most of this code is framework agnostic, but this doc
describes how to use it in the context of Symfony 3.

Aim of this bundle is to simplify integration of 
`https://datatables.net/` in your app. It helps to keep
your controllers short and database access layer clean.

Installation
============

Step 1: Download the Bundle
---------------------------
Add this bundle to your project as if you were adding your
own Bundle:
```
+-- src
|   +-- AppBundle
|   +-- DataTablesBundle
```
Examples
========
Basic example
-------------
It shows how to build response for datatable which consists of four columns:
user id, first name, last name
and name of company assigned to this user.

`AppBundle\DataTables\Users.php`
```php
<?php
namespace AppBundle\DataTables;

use DataTablesBundle\Dql\Column;
use DataTablesBundle\Dql\ColumnCollection;
use DataTablesBundle\Repo;
use AppBundle\Entity\User;

class Users extends Repo
{
    private $columnsCollection;

    protected function getColumns()
    {
        if (!$this->columnsCollection) {
            $c = new ColumnCollection();

            $c->addColumn(new Column('id', 'user'));
            $c->addColumn(new Column('firstName', 'user'));
            $c->addColumn(new Column('lastName', 'user'));
            $c->addColumn(new Column('name', 'company'));
            $this->columnsCollection = $c;
        }
        return $this->columnsCollection;
    }

    protected function getQueryBuilder()
    {
        $qb = $this->em->createQueryBuilder()
            ->select($this->buildColumnsDQL())
            ->from($this->getFromEntityName(), $this->getFromAlias())
            ->join('user.company', 'company');
        return $qb;
    }

    protected function getFromEntityName()
    {
        return User::class;
    }

    protected function getFromAlias()
    {
        return 'user';
    }
}
```
`AppBundle\Resources\config\services.yml`
```yml
services:
  datatables.users:
    class: AppBundle\DataTables\Users
    arguments: ['@doctrine.orm.entity_manager']
```

`AppBundle\Controller\DefaultController.php`
```php
<?php

namespace AppBundle\Controller;
use DataTablesBundle\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function usersJsonAction(Request $request)
    {
        $dataTablesRequest = new \DataTablesBundle\Request\Request($request->query->all());
        $srv = $this->get('datatables.users');
        $dataTablesResponse = $srv->find($dataTablesRequest);
        return $this->json($dataTablesResponse);
    }
}
```
CONCAT_WS example
-----------------
This example assumes that your DQL supports CONCAT_WS function.
To achieve it, you can use `https://github.com/beberlei/DoctrineExtensions`

Let's look again at first example. Now we want to concatenate 
first name and last name as one column. Changes need be done in one file:

`AppBundle\DataTables\Users.php`
```php
<?php
//...
use DataTablesBundle\Dql\ConcatWs;
//...
protected function getColumns()
{
    if (!$this->columnsCollection) {
        $c = new ColumnCollection();

        $c->addColumn(new Column('id', 'user'));
        $c->addColumn($this->firstAndLastNameColumnFactory());
        $c->addColumn(new Column('name', 'company'));
        $this->columnsCollection = $c;
    }
    return $this->columnsCollection;
}

private function firstAndLastNameColumnFactory()
{
    $c = new ColumnCollection();
    $c->addColumn(new Column('firstName', 'user'));
    $c->addColumn(new Column('lastName', 'user'));
    $concatWs = new ConcatWs($c, 'first_and_last_name', ' ');
    $concatWs->setFilterable(true);
    $concatWs->setLikeType(ColumnAbstract::LIKE_TYPE_RIGHT);
    return $concatWs;
}
```
Note that we will also have ability to search by this 
concatenated column in
our datatable.