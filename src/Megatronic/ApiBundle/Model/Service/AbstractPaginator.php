<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 31/07/18
 * Time: 13:54
 */

namespace MegatronicApiBundle\Model\Service;


use MegatronicApiBundle\Model\IPaginate;
use MegatronicApiBundle\Model\ISearch;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

abstract  class AbstractPaginator implements IPaginate
{

    /**
     * @var ObjectManager
     */
    protected $om;
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function paginate($metaClassName, $filters = array(),
                             $getObj = false,
                             $injectFilter = false,
                             $orderSet = 0)
    {
        /**
         * @var ISearch $repository
         */
        $repository = $this->om->getRepository($metaClassName);
        $this->checkRepository($repository);

        $filters = ($injectFilter) ? $filters : $this->getFilters($repository, $filters);
        $orderParams = $this->getOrderParams($repository, $orderSet);

        $orderColumn = $orderParams['orderColumn'];
        $orderDirection = $orderParams['orderDirection'];

        $pagination_params = $this->getPaginationParams();

        $length = $pagination_params['length'];
        $start = $pagination_params['start'];

        return $repository->search($filters, $orderColumn, $orderDirection, $start, $length, $getObj);

    }

    private function checkRepository(ObjectRepository $repository)
    {
        if (!$repository instanceof ISearch) {
            throw new \InvalidArgumentException(
                sprintf(
                    "the classe %s have to implement %s interface",
                    get_class($repository),
                    ISearch::class
                )
            );
        }
    }

    protected abstract function getPaginationParams();
    protected abstract function getOrderParams(ISearch $repository, $orderSet);
    protected abstract function getFilters(ISearch $repository, $filters);


}