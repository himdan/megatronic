<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 31/07/18
 * Time: 14:31
 */

namespace MegatronicApiBundle\Model\Repository;

use MegatronicApiBundle\Model\IJson;

trait SearchTrait
{

    protected $columnMaps = [];
    protected $filtrableFields = [];
    /**
     * @param $filters
     * @param $sortColumn
     * @param string $sortOrder
     * @param int $page
     * @param null $max
     * @param bool $getObj
     * @return array
     */
    public function search($filters, $sortColumn, $sortOrder = 'asc', $page = 0, $max = null, $getObj = false)
    {
        $recordsTotal = $this->simpleCount([]);
        if (count(array_filter($filters))) {
            $recordsFiltered = $this->simpleCount($filters);
        } else {
            $recordsFiltered = $recordsTotal;
        }

        $data = array();
        foreach ($this->buildSearchQuery($filters, $sortColumn, $sortOrder, $page, $max)->execute() as $result) {
            $data[] = ($getObj) ? $result : $this->toJson($result);
        }
        return compact('data', 'recordsFiltered', 'recordsTotal', 'filters');
    }

    /**
     * @param $object
     * @return array
     */
    public function toJson($object)
    {
        if ($object instanceof IJson) {
            return $object->toJson();
        } else {
            return [];
        }
    }

    /**
     * @return mixed
     */
    public function getFilterableFields()
    {
        return $this->filtrableFields;
    }

    public function getFiltrableFieldMap($index)
    {
        return(array_key_exists($index, $this->filtrableFields))?$this->filtrableFields[$index]:'';
    }

    /**
     * @param $index
     * @param int $mapIndex
     * @return mixed
     */
    public function getOrderColumn($index)
    {
        return(array_key_exists($index, $this->columnMaps))?$this->columnMaps[$index]:$this->columnMaps[0];
    }

    /**
     * @param array $filter
     * @return array
     */
    public function simpleSearch($filter = [])
    {
        return $this->search($filter, $this->getOrderColumn(0));
    }

    /**
     * @param array $filter
     * @return array
     */
    public function simpleObjectSearch($filter = [])
    {
        return $this->search($filter, $this->getOrderColumn(0), 'asc', 0, null, true);
    }

    /**
     * @param array $filter
     * @return mixed
     */
    public function simpleCount($filter = [])
    {
        return  $this->buildSearchQuery($filter, $this->getOrderColumn(0), 'asc', 0, null, true)->execute();
    }
}
