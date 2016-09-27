<?php
/**
 * Created by PhpStorm.
 * User: seth
 * Date: 4/12/16
 * Time: 11:29 PM
 */

namespace Core\MediaBundle\Repository;


use Core\CoreBundle\Repository\CoreDocumentRepository;

class MediaRepository extends CoreDocumentRepository
{
    /**
     * @param string $search
     * @param int $page
     * @param int $count
     * @param array $sort
     * @return array
     */
    public function getLibrary($search = '', $page = 1, $count = 20, $sort = array())
    {
        $qb = $this->createQueryBuilder();

        if($page > 0 && $count > 0) {
            $qb->limit($count)->skip(($page - 1) * $count);
        }

        if(count($sort) > 0) {
            $qb->sort($sort);
        }

        return $qb->getQuery()->execute()->toArray(0);
    }
}