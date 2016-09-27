<?php
/**
 * Created by PhpStorm.
 * User: seth
 * Date: 4/21/16
 * Time: 11:08 PM
 */

namespace Core\AppBundle\Services;


use Core\AppBundle\Document\Bird;
use Core\CoreBundle\CoreApp\JsonWrapper;
use Core\CoreBundle\Service\CoreService;
use Symfony\Component\DependencyInjection\Container;

class BirdService extends CoreService
{
    public function __construct(Container $container)
    {
        parent::__construct($container, Bird::class);
    }

    /**
     * @param int $page
     * @param int $count
     * @param string $sort
     * @return array
     */
    public function getBirdList($page, $count, $sort)
    {
        $count++;
        $result = $this->repository()->getBirds(null, $page, $count, $sort);
        $birds  = array_slice($result, 0, $count-1);

        return JsonWrapper::dataList('success', $birds, isset($result[$count-1]));
    }

}