<?php
/**
 * Created by PhpStorm.
 * User: seth
 * Date: 4/10/16
 * Time: 12:32 PM
 */

namespace Core\AppBundle\Repository;


use Core\CoreBundle\Repository\CoreDocumentRepository;

class BirdRepository extends CoreDocumentRepository
{
    public function getBirds($search = '', $page = 1, $count = 20, $sort = array())
    {
        $result = $this->getAdminIndex($search, $page, $count, $sort);

        return $result;
    }
}