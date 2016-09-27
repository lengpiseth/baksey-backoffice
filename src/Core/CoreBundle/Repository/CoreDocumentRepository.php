<?php
/**
 * Created by PhpStorm.
 * User: seth
 * Date: 4/14/16
 * Time: 6:57 PM
 */

namespace Core\CoreBundle\Repository;


use Doctrine\ODM\MongoDB\DocumentRepository;
use Knp\Component\Pager\Paginator;

class CoreDocumentRepository extends DocumentRepository {
	/** @var  Paginator $paginator */
	private $paginator;
	private $page  = 1;
	private $count = 20;

	/**
	 * @param string $field
	 *
	 * @return array
	 * @throws \Doctrine\ODM\MongoDB\MongoDBException
	 */
	public function getDistinctField($field) {
		return $this->createQueryBuilder()
		            ->distinct($field)
		            ->getQuery()
		            ->execute()
		            ->toArray();
	}

	/**
	 * @param string $search
	 * @param array $sort
	 * @param bool $withPagination
	 *
	 * @return array
	 * @internal param int $page
	 * @internal param int $count
	 */
	public function getAdminIndex($search = null, $sort = array(), $withPagination = false) {
		$qb = $this->createQueryBuilder();

		$qb->addAnd($qb->expr()->field('delete')->equals(false));

		if($search != null && strlen(trim($search)) > 1) {
			$search = trim($search);
			$qb->addOr($qb->expr()->field('name')->equals(new \MongoRegex('/' . $search . '/i')));
			foreach(explode(' ', $search) as $string) {
				if(strlen($string) > 2) {
					$qb->addOr($qb->expr()->field('name')->equals(new \MongoRegex('/^' . $string . '/i')));
				}
			}
		}

		if($this->page > 0 && $this->count > 0) {
			$qb->limit($this->count)->skip(($this->page - 1) * $this->count);
		}

		if(count($sort) > 0) {
			$qb->sort($sort);
		}

		return ($withPagination) ? $this->paginator->paginate($qb, $this->page, $this->count) : $qb->getQuery()->execute()->toArray(0);
	}

	/**
	 * @param Paginator $paginator
	 *
	 * @return $this
	 */
	public function setPaginator(Paginator $paginator) {
		$this->paginator = $paginator;

		return $this;
	}

	public function setPageNumber($page = 1) {
		$this->page = $page;

		return $this;
	}

	public function setCount($count = 20) {
		$this->count = $count;

		return $this;
	}
}