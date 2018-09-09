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
        $recordsTotal = $this->buildSearchQuery([], $sortColumn, 'asc', 0, null, true)->execute();
        if (count(array_filter($filters))) {
            $recordsFiltered = $this->buildSearchQuery($filters, $sortColumn, 'asc', 0, null, true)->execute();
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

    /**
     * @param $index
     * @param int $mapIndex
     * @return mixed
     */
    public function getOrderColumn($index)
    {
        return(array_key_exists($index, $this->columnMaps))?$this->columnMaps[$index]:$this->columnMaps[0];
    }
}
