<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 31/07/18
 * Time: 12:30
 */

namespace MegatronicApiBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use MegatronicApiBundle\Model\IJson;

/**
 * Class MegatronicResource
 * @package MegatronicApiBundle\Document
 * @ODM\Document(collection="MegatronicResource",repositoryClass="MegatronicApiBundle\Repository\MegatronicResourceRepository")
 */
class MegatronicResource implements IJson
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
     * @ODM\Field(type="collection")
     */
    protected $applications;
    /**
     * @var MegatronicContext
     * @ODM\ReferenceOne(targetDocument="MegatronicContext")
     */
    protected $context;
    /**
     * @var ArrayCollection
     * @ODM\ReferenceMany(targetDocument="MegatronicUser")
     */
    protected $downloaders;

    public function __construct()
    {
        $this->applications = [];
        $this->downloaders = new ArrayCollection();
    }

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

    /**
     * @return MegatronicContext
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param MegatronicContext $context
     * @return MegatronicResource
     */
    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getDownloaders()
    {
        return $this->downloaders;
    }

    /**
     * @param ArrayCollection $downloaders
     * @return MegatronicResource
     */
    public function setDownloaders($downloaders)
    {
        $this->downloaders = $downloaders;
        return $this;
    }

    public function toJson()
    {
        return [
            'id' => $this->getId(),
            'type' => $this->getType(),
            'extension' => $this->getExtension(),
            'meta' => $this->getMeta(),
            'description' => $this->getDescription(),
            'applications' => $this->getApplications()
        ];
    }
}
