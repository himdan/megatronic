<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 31/07/18
 * Time: 12:30
 */

namespace MegatronicApiBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class MegatronicResource
 * @package MegatronicApiBundle\Document
 * @ODM\Document(collection="MegatronicResource",repositoryClass="MegatronicApiBundle\Repository\MegatronicResourceRepository")
 */
class MegatronicResource
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
    protected $type;
    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $description;
    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $meta;
    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $extension;
    /**
     * @var array
     * @ODM\Field(type="hash")
     */
    protected $applications;

    public function __construct()
    {
        $this->applications = [];
    }

    /**
     * @return ODM\Id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return MegatronicResource
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return MegatronicResource
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param string $meta
     * @return MegatronicResource
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     * @return MegatronicResource
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @return array
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * @param array $applications
     * @return MegatronicResource
     */
    public function setApplications($applications)
    {
        $this->applications = $applications;
        return $this;
    }
}
