<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 04/09/18
 * Time: 21:21
 */

namespace MegatronicApiBundle\Model\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

trait CrudTrait
{

    protected static $crudModel;
    protected static $crudType;

    /**
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        $modelInstance = new static::$crudModel;
        /**
         * @var FormInterface $modelForm
         */
        $modelForm =  $this->createForm(static::$crudType, $modelInstance);
        $modelForm->handleRequest($request);
        if ($modelForm->isValid() && $modelForm->isSubmitted()) {
             return $this->saveData($modelInstance);
        } else {
            return $this->handleInvalid();
        }
    }

    /**
     * @param $data
     * @param $modelInstance
     * @return mixed
     */
    public function patch($data, $modelInstance)
    {
        /**
         * @var FormInterface $modelForm
         */
        $modelForm =  $this->createForm(static::$crudType, $modelInstance);
        $modelForm->submit($data, false);
        if ($modelForm->isValid() && $modelForm->isSubmitted()) {
            return $this->saveData($modelInstance);
        } else {
            return $this->handleInvalid();
        }
    }

    /**
     * @param $modelInstance
     * @param Request $request
     * @return mixed
     */
    public function update($modelInstance, Request $request)
    {
        /**
         * @var FormInterface $modelForm
         */
        $modelForm =  $this->createForm(static::$crudType, $modelInstance);
        $modelForm->handleRequest($request);
        if ($modelForm->isValid() && $modelForm->isSubmitted()) {
            return $this->saveData($modelInstance);
        } else {
            $this->handleInvalid();
        }
    }

    /**
     * @param $object
     * @return mixed
     */
    public function delete($object)
    {
        /**
         * @var ObjectManager $dm
         */
        $dm = $this->get('doctrine_mongodb.odm.document_manager');
        $dm->persist($object);
        try {
            $dm->flush();
            return $object;
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * @param $object
     * @return mixed
     */
    private function saveData($object)
    {
        /**
         * @var ObjectManager $dm
         */
        $dm = $this->getObjectManager();
        $dm->remove($object);
        try {
            $dm->flush();
            return $object;
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
