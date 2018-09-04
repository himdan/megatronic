<?php

namespace MegatronicApiBundle\Controller;

use MegatronicApiBundle\Document\MegatronicResource;
use MegatronicApiBundle\Service\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;

class MegatronicResourceController extends BaseController
{

    public function listAction(Paginator $paginator)
    {
        $megatronicResources = $paginator->paginate(MegatronicResource::class);
        return new JsonResponse($megatronicResources);
    }
}
