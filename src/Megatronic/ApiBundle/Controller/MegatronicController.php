<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 20/09/18
 * Time: 18:34
 */

namespace MegatronicApiBundle\Controller;

use MegatronicApiBundle\Model\Controller\MegatronicCrudController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class MegatronicController
 * @package MegatronicApiBundle\Controller
 */
abstract class MegatronicController extends MegatronicCrudController
{
    /**
      * @param null $object
      * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
      */
    protected function handleSuccess($object = null)
    {
        $json = $this->parseObjectAsJson($object);
        return new JsonResponse($json, 200);
    }

    protected function handleException(\Exception $e)
    {
        $exceptionJson = [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ];
        return new JsonResponse($exceptionJson, 422);
    }

    protected function handleInvalid(FormInterface $form)
    {
        return new JsonResponse([], 422);
    }

    protected function getObjectManager()
    {
        return $this->get('doctrine_mongodb.odm.document_manager');
    }
}
