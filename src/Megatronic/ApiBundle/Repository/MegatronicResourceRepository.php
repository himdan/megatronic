<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 31/07/18
 * Time: 13:41
 */

namespace MegatronicApiBundle\Repository;


use MegatronicApiBundle\Model\Repository\AbstractDocumentRepository;

class MegatronicResourceRepository extends AbstractDocumentRepository
{
    protected $filtrableFields = [
        'type' => 'empty',
        'description' => 'empty',
        'meta' => 'empty',
        'extension' => 'empty'
    ];
    protected $columnMaps  = [
        0 => 'id',
        'type' => 'type',
        'description' => 'description',
        'meta' => 'meta',
        'extension' => 'extension'
    ];
    /**
     * @param array $data
     * @param $sortColumn
     * @param string $sortOder
     * @param int $start
     * @param null $length
     * @param bool $countOnly
     * @return mixed
     */
    public function buildSearchQuery($data = [], $sortColumn, $sortOrder = 'asc', $start = 0, $length = null, $countOnly = false)
    {
        $qb = $this->createQueryBuilder()->eagerCursor(true);
        if ($countOnly) {
            $qb->count();
        }
        if ($length) {
            $qb
                ->limit($length)
                ->skip($start * $length);
        }
        $qb->sort($sortColumn, $sortOrder);
        return $qb->getQuery();
    }





}