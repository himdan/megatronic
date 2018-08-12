<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 02/08/18
 * Time: 13:01
 */

namespace MegatronicApiBundle\Service;


use MegatronicApiBundle\Model\ISearch;
use MegatronicApiBundle\Model\Service\AbstractPaginator;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class Paginator extends AbstractPaginator
{

    /**
     * @var Request
     */
    protected $request;
    public function __construct(ObjectManager $om, RequestStack $request)
    {
        parent::__construct($om);
        $this->request = $request->getCurrentRequest();
    }

    public function getFilters(ISearch $repository, $filters = [])
    {
        foreach ($repository->getFilterableFields() as $fieldName => $allValues) {
            $valueFromRequest = $this->request->get($fieldName);
            if (is_array($valueFromRequest)) {
                $fieldValue = array_filter($valueFromRequest);
            } else {
                if (!$valueFromRequest || 0 === strcasecmp($valueFromRequest, $allValues)) {
                    $fieldValue = null;
                } else {
                    $fieldValue = $valueFromRequest;
                }
            }

            $filters[$fieldName] = $fieldValue;
        }
        $filters['locale'] = $this->request->getLocale();
        return $filters;
    }

    public function getFiltersByRepository($repositoryName, $filters = [])
    {
        $repository = $this->om->getRepository($repositoryName);
        return $this->getFilters($repository, $filters);
    }

    public function getOrderParams(ISearch $repository, $orderSet = 0)
    {

        $order = $this->request->get('order', array(array('column' => 0, 'dir' => 'asc')));

        $orderColumn = $repository->getOrderColumn($order[0]['column'], $orderSet);
        $orderDirection = $order[0]['dir'];

        return compact('orderColumn', 'orderDirection');
    }

    public function getPaginationParams()
    {
        $length = $this->request->get('length');
        $length = $length && $length >= 0 ? $length : 0;

        $start = $this->request->get('start');
        $start = $length ? ($start && ($start != -1) ? $start : 0) / $length : 0;

        return compact('start', 'length');
    }
}