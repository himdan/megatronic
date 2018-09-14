<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 14/09/18
 * Time: 20:12
 */

namespace MegatronicApiBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use MegatronicApiBundle\Model\IJson;

/**
 * Class MegatronicContext
 * @package MegatronicApiBundle\Document
 * @ODM\Document(collection="MegatronicContext",repositoryClass="MegatronicApiBundle\Repository\MegatronicContextRepository")
 */
class MegatronicContext implements IJson
{
    /**
     * @var int
     * @ODM\Id
     */
    protected $id;
    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $name;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return MegatronicContext
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function toJson()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName()
        ];
    }
}
