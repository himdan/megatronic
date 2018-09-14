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
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataInfo;
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
            if (!array_key_exists($fieldName, $this->filtrableFields)) {
                continue;
            }
            if (isset($fieldValue)) {
                if (false !==strpos($fieldName, '_like')) {
                    // case insensitive
                    $qb
                        ->field($this->getOrderColumn($fieldName))
                        ->equals(new \MongoRegex('/'.$fieldValue.'/i'));
                } elseif (false !== strpos($fieldName, '_gen')) {
                    // case insensitive and generic
                    $qb
                        ->field($this->getOrderColumn($fieldName))
                        ->equals(new \MongoRegex('/.*'.$fieldValue.'.*/i'));
                } elseif (false !== strpos('_in', $fieldName) && is_array($fieldValue)) {
                    // cas hash attribute
                    $qb
                        ->field($this->getOrderColumn($fieldName))
                        ->equals($fieldValue);
                } elseif (($this->refIn($fieldName, $pos))&& is_array($fieldValue)) {
                    //case reference one with criteria

                    $prop = (explode('_', $fieldName))[0];
                    $associatedRepository = $this->resolveRepositoryByPropertyName($prop);
                    $ids = $associatedRepository->filterIdsByCriteria($fieldValue);
                    $qb
                        ->field(sprintf('%s.id', $prop))
                        ->in($ids);
                } else {
                    $qb
                        ->field($this->getOrderColumn($fieldName))
                        ->equals($fieldValue);
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
            //collection type
            if ("collection" === strtolower($meta['type'])) {
                $inIndex = sprintf('%s_in', $fieldName);
                $this->columnMaps[$inIndex] = $fieldName;
            }
        }
        foreach ($classMetaData->associationMappings as $fieldName => $meta) {
            $isReference = $meta['reference'] === true;
            $isOne = $meta['association'] === ClassMetadataInfo::REFERENCE_ONE;
            if ($isReference&&$isOne) {
                $genericIndex = sprintf('%s_refIn', $fieldName);
                $this->columnMaps[$genericIndex] = $fieldName;
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
            // meta type string case
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
            //meta type date case
            if ("date" === strtolower($meta['type'])) {
                // case Equal
                $this->filtrableFields[$fieldName] = self::EMPTY_SET;
                //case low or equals
                $letIndex = sprintf('%s_let', $fieldName);
                $this->filtrableFields[$letIndex] = self::EMPTY_SET;
                //case  lower then
                $ltIndex = sprintf('%s_lt', $fieldName);
                $this->filtrableFields[$ltIndex] = self::EMPTY_SET;
                //case greater or equal
                $getIndex = sprintf('%s_gte', $fieldName);
                $this->filtrableFields[$getIndex] = self::EMPTY_SET;
            }
            //meta type collection
            if ("collection" === strtolower($meta['type'])) {
                $genericIndex = sprintf('%s_in', $fieldName);
                $this->filtrableFields[$genericIndex] = self::EMPTY_SET;
            }
        }
        foreach ($classMetaData->associationMappings as $fieldName => $meta) {
            $isReference = $meta['reference'] === true;
            $isOne = $meta['association'] === ClassMetadataInfo::REFERENCE_ONE;
            if ($isReference&&$isOne) {
                $genericIndex = sprintf('%s_refIn', $fieldName);
                $this->filtrableFields[$genericIndex] = self::EMPTY_SET;
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

    /**
     * @param $property
     */
    protected function resolveMetaByPropertyName($property)
    {
        $classMetaData = $this->getClassMetadata();
        $meta = $classMetaData->associationMappings[$property];
        return $meta['targetDocument'];
    }

    /**
     * @param $property
     * @return AbstractDocumentRepository
     * @throws \Exception
     */
    protected function resolveRepositoryByPropertyName($property)
    {
        $className = $this->resolveMetaByPropertyName($property);
        $repository = $this->dm->getRepository($className);
        if (!($repository instanceof AbstractDocumentRepository)) {
            throw new \Exception(sprintf('%s must extend AbstractDocumentRepository', $this->getClassName($repository)));
        }

        return $repository;
    }

    protected function filterIdsByCriteria($filters)
    {
        $qb = $this->createQueryBuilder()->eagerCursor(true);
        $this->populateQb($qb, $filters);
        $q = $qb->getQuery();
        $ids = [];
        foreach ($q->execute() as $object) {
            array_push($ids, $object->getId());
        }
        return $ids;
    }
    protected function refIn($fieldName, &$pos)
    {
        return (false !== $pos=strpos($fieldName, '_refIn'));
    }
}
