<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 31/07/18
 * Time: 14:12
 */

namespace MegatronicApiBundle\Model\Repository;

use MegatronicApiBundle\Model\ISearch;
use Doctrine\ORM\EntityRepository;

abstract class AbstractEntityRepository extends EntityRepository implements ISearch
{
    use SearchTrait;
}
