<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 29/07/18
 * Time: 13:36
 */

namespace MegatronicApiBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * Class MegatronicUser
 * @ODM\Document(collection="MegatronicUser")
 */
class MegatronicUser extends BaseUser
{
    /**
     * @var int
     * @ODM\Id
     */
    protected $id;
}
