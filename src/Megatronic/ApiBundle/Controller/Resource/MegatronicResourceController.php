<?php

namespace MegatronicApiBundle\Controller\Resource;

use MegatronicApiBundle\Document\MegatronicResource;
use MegatronicApiBundle\Controller\MegatronicController as BaseController;
use MegatronicApiBundle\Form\MegatronicResourceType;

class MegatronicResourceController extends BaseController
{
    public static $crudModel = MegatronicResource::class;
    public static $crudType = MegatronicResourceType::class;

    /**
     * @param MegatronicResource $resource
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(MegatronicResource $resource)
    {
        return $this->handleSuccess($resource);
    }
}
