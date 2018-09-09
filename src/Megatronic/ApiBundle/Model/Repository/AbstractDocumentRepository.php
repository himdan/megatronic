<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 31/07/18
 * Time: 14:06
 */

namespace MegatronicApiBundle\Model\Repository;

use Doctrine\MongoDB\Query\Builder;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\UnitOfWork;
use MegatronicApiBundle\Model\ISearch;
use Doctrine\ODM\MongoDB\DocumentRepository;

abstract class AbstractDocumentRepository extends DocumentRepository implements ISearch
{

    use SearchTrait;
    const EMPTY_SET = 'empty';
    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $classMetadata)
    {
        parent::__construct($dm, $uow, $classMetadata);
        $this->buildFiltrableFields();
        $this->buildColumnMap();
    }

    /**
     * override to change how you filter your records
     * @param Builder $qb
     * @param $data
     */
    protected function populateQb(Builder $qb, $data)
    {
        foreach ($data as $fieldName => $fieldValue) {
            if ($fieldName === 'locale') {
                continue;
            }
            if (isset($fieldValue)) {
                if (false !==strpos('_like', $fieldName)) {
                    // case insensitive
                    $qb->field($this->getOrderColumn($fieldName));
                    $qb->equals(new \MongoRegex('/'.$fieldValue.'/i'));
                } elseif (false !== strpos('_gen', $fieldName)) {
                    // case insensitive and generic
                    $qb->field($this->getOrderColumn($fieldName));
                    $qb->equals(new \MongoRegex('/.*'.$fieldValue.'.*/i'));
                } else {
                    // case sentitive and equality
                    $qb->field($this->getOrderColumn($fieldName));
                    $qb->equals($fieldValue);
                }
            }
        }
    }

    /**
     * Build the column map for sorting
     */
    protected function buildColumnMap()
    {
        $classMetaData = $this->getClassMetadata();
        $this->columnMaps[0] = 'id';
        foreach ($classMetaData->fieldMappings as $fieldName => $meta) {
            if (!isset($meta['type']) || "string" === strtolower($meta['type']) || "integer" === strtolower($meta['type'])) {
                //case senstive and equal
                $this->columnMaps[$fieldName] = $fieldName;
                // case insenstive and like
                $genericIndex = sprintf('%s_gen', $fieldName);
                $this->columnMaps[$genericIndex] = $fieldName;
                // case simple like
                $likeIndex = sprintf('%s_like', $fieldName);
                $this->columnMaps[$likeIndex] = $fieldName;
            }
        }
    }

    /**
     * Build filter field in order
     * to change the accepted value
     * from the filering stack
     */
    protected function buildFiltrableFields()
    {
        $classMetaData = $this->getClassMetadata();
        foreach ($classMetaData->fieldMappings as $fieldName => $meta) {
            if (!isset($meta['type']) || "string" === strtolower($meta['type']) || "integer" === strtolower($meta['type'])) {
                //case senstive and equal
                $this->filtrableFields[$fieldName] = self::EMPTY_SET;
                // case insenstive and like
                $genericIndex = sprintf('%s_gen', $fieldName);
                $this->filtrableFields[$genericIndex] = self::EMPTY_SET;
                // case simple like
                $likeIndex = sprintf('%s_like', $fieldName);
                $this->filtrableFields[$likeIndex] = self::EMPTY_SET;
            }
        }
    }

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
        $this->populateQb($qb, $data);
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
