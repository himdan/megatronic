<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 04/09/18
 * Time: 21:16
 */

namespace MegatronicApiBundle\Model\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

abstract class MegatronicBaseController extends Controller implements ICrud
{
    use CrudTrait;

    /**
     * @param Request $request
     * @return mixed
     */
    public function createAction(Request $request)
    {

        return $this->create($request);
    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function updateAction($id, Request $request)
    {
        /**
         *  we resolve it without param conventer
         */
        $object = $this->getObjectManager()->find(static::$crudModel, $id);
        return $this->update($object, $request);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteAction($id)
    {
        /**
         *  we resolve it without param conventer
         */
        $object = $this->getObjectManager()->find(static::$crudModel, $id);
        return $this->delete($object);
    }
    public function patchAction($id, Request $request)
    {
        /**
         *  we resolve it without param conventer
         */
        $object = $this->getObjectManager()->find(static::$crudModel, $id);
        return $this->patch($request->request->all(), $object);
    }

    /**
     * Override to implement the way how to handle Exception
     * @param \Exception $e
     * @return mixed
     */
    abstract protected function handleException(\Exception $e);

    /**
     * Overrid to implement the way to handle invalid data and failed Constraint
     */
    abstract protected function handleInvalid();

    /**
     * Manager abstraction Layer
     * @return ObjectManager
     */
    abstract protected function getObjectManager();
}
