<?php
namespace DataTablesBundle;

use DataTablesBundle\Dql\ColumnAbstract;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use DataTablesBundle\Dql\ColumnCollection;
use DataTablesBundle\Request\Request;

abstract class Repo
{
    protected $em;

    /**
     * @var Request
     */
    protected $dataTablesRequest;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return ColumnCollection
     */
    abstract protected function getColumns();

    /**
     * @return QueryBuilder
     */
    abstract protected function getQueryBuilder();

    /**
     * @return string
     */
    abstract protected function getFromEntityName();

    /**
     * @return string
     */
    abstract protected function getFromAlias();

    /**
     * @param Request $request
     * @return Response
     */
    public function find(Request $request)
    {
        $this->dataTablesRequest = $request;
        $response = new Response();
        $response->draw = $request->draw;

        $response->data = $this->findRows();
        $response->recordsFiltered = $this->countRecordsFiltered();

        $response->recordsTotal = $this->em->createQuery('SELECT COUNT(c) FROM ' . $this->getFromEntityName() . ' c')->getSingleScalarResult();

        return $response;
    }

    private function findRows()
    {
        $qb = $this->getQueryBuilder();
        $this->addOrderToQuery($qb);
        $query = $qb->getQuery()
            ->setFirstResult($this->dataTablesRequest->start)
            ->setMaxResults($this->dataTablesRequest->length);
        return array_map('array_values', $query->getArrayResult());
    }

    private function countRecordsFiltered()
    {
        $count = $this->getQueryBuilder()
            ->select('COUNT(' . $this->getFromAlias() . ')')
            ->getQuery()
            ->getSingleScalarResult();
        return $count;
    }

    protected function buildColumnsDQL()
    {
        $result = '';
        $i = 0;
        foreach ($this->getColumns() as $column) {
            $result .= $column->getNameBeforeAsStatement() . ' AS ' . $column->getAlias() . ',';
            $i++;
        }
        return rtrim($result, ',');
    }

    protected function addOrderToQuery(QueryBuilder $DQL)
    {
        $columns = $this->getColumns();
        $orders = $this->dataTablesRequest->order;
        foreach ($orders as $order) {
            $DQL->addOrderBy($columns->getColumnByIndex($order->column)->getAlias(), $order->dir);
        }
    }

    protected function addFilteredColumnsToQuery(QueryBuilder $DQL)
    {
        $where = '';
        foreach ($this->getColumns() as $column) {
            if ($column->isFilterable()) {
                $likeValue = $this->dataTablesRequest->search->value;
                switch ($column->getLikeType()) {
                    case ColumnAbstract::LIKE_TYPE_LEFT:
                        $likeValue = "'%" . $likeValue . "'";
                        break;
                    case ColumnAbstract::LIKE_TYPE_RIGHT:
                        $likeValue = "'" . $likeValue . "%'";
                        break;
                    case ColumnAbstract::LIKE_TYPE_DOUBLE:
                        $likeValue = "'%" . $likeValue . "%'";
                }
                $where .= $DQL->expr()->like($column->getNameBeforeAsStatement(), $likeValue) . ' OR ';
            }
        }
        if ($where) {
            $where = substr($where, 0, -4);
        }
        $DQL->andWhere($where);
    }
}