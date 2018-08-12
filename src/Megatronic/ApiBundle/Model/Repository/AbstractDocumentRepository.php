<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 31/07/18
 * Time: 14:06
 */

namespace MegatronicApiBundle\Model\Repository;


use MegatronicApiBundle\Model\ISearch;
use Doctrine\ODM\MongoDB\DocumentRepository;

 abstract  class AbstractDocumentRepository extends DocumentRepository implements ISearch
{

     use SearchTrait;


}