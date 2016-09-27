<?php
/**
 * Created by PhpStorm.
 * User: seth
 * Date: 4/12/16
 * Time: 8:13 PM
 */

namespace Core\AppBundle\Controller;


use Core\AppBundle\Document\Bird;
use Core\AppBundle\Form\Type\BirdFormType;
use Core\CoreBundle\Controller\CoreAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminBirdController
 * @package Core\AppBundle\Controller
 * @Route("/admin/bird")
 */
class AdminBirdController extends CoreAdminController {
	public function __construct() {
		parent::__construct('AppBundle', 'Bird');
	}

	/**
	 * @Route(path="/", name="admin_bird_index")
	 * @Template(":AppBundle:bird/index.html.twig")
	 * @param Request $request
	 *
	 * @return array
	 */
	public function indexAction(Request $request) {
		$page        = $request->query->getInt('page', 1);
		$count       = $request->query->getInt('count', 10);
		$searchValue = $request->query->get('search_value', null);

		$birdRepo = $this->dm()->getRepository('AppBundle:Bird');
		$birds    = $birdRepo
			->setPaginator($this->paginator())
			->setPageNumber($page)
			->setCount($count)
			->getAdminIndex($searchValue, array('createDate' => - 1), true);

		return array(
			'documents'   => $birds,
			'page'        => $page,
			'searchValue' => $searchValue
		);
	}

	/**
	 * @Route(path="/show/{id}", name="admin_bird_show")
	 * @Template(":AppBundle:bird/show.html.twig")
	 * @param Bird $document
	 *
	 * @return array
	 */
	public function showAction(Bird $document) {
		return array(
			'document' => $document
		);
	}

	/**
	 * @Route(path="/new", name="admin_bird_new")
	 * @Template(":AppBundle:bird/new.html.twig")
	 * @return array
	 */
	public function newAction() {
		$autoCompleteList = $this->getAutoCompleteList();

		$document = new Bird();
		$form     = $this->createForm(BirdFormType::class, $document, array(
			'action' => $this->generateUrl('admin_bird_create')
		));

		return array(
			'form'             => $form->createView(),
			'document'         => $document,
			'autoCompleteList' => $autoCompleteList
		);
	}

	/**
	 * @Route(path="/create", name="admin_bird_create")
	 * @Method({"POST"})
	 * @Template(":AppBundle:bird/new.html.twig")
	 * @param Request $request
	 *
	 * @return array
	 */
	public function createAction(Request $request) {
		$document = new Bird();

		$form = $this->createForm(BirdFormType::class, $document, array(
			'action' => $this->generateUrl('admin_bird_create')
		));
		$form->handleRequest($request);

		if($form->isValid()) {
			$document->setAuthor($this->get('security.token_storage')->getToken()->getUser());
			$this->dm()->persist($document);
			$this->dm()->flush();

			$this->addSuccessFlashMsg($document->getName(), 'saved');

			return $this->redirectToRoute('admin_bird_edit', array('id' => $document->getId()));
		}

		$autoCompleteList = $this->getAutoCompleteList();

		return array(
			'form'             => $form->createView(),
			'document'         => $document,
			'autoCompleteList' => $autoCompleteList
		);
	}

	/**
	 * @Route(path="/edit/{id}", name="admin_bird_edit")
	 * @Template(":AppBundle:bird/new.html.twig")
	 * @param Bird $document
	 *
	 * @return array
	 */
	public function editAction(Bird $document) {
		$form = $this->createForm(BirdFormType::class, $document, array(
			'action' => $this->generateUrl('admin_bird_update', array('id' => $document->getId()))
		));

		$autoCompleteList = $this->getAutoCompleteList();

		$deleteForm = $this->createDeleteForm($document->getId(), $this->generateUrl('admin_bird_delete', array('id' => $document->getId())));

		return array(
			'form'             => $form->createView(),
			'document'         => $document,
			'deleteForm'       => $deleteForm->createView(),
			'autoCompleteList' => $autoCompleteList
		);
	}

	/**
	 * @Route(path="/update/{id}", name="admin_bird_update")
	 * @Method({"POST"})
	 * @Template(":AppBundle:bird/new.html.twig")
	 * @param Request $request
	 * @param Bird $document
	 *
	 * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
	 * @internal param Request $request
	 */
	public function updateAction(Request $request, Bird $document) {
		$form = $this->createForm(BirdFormType::class, $document);
		$form->handleRequest($request);

		if($form->isValid()) {
			$this->dm()->persist($document);
			$this->dm()->flush();

			$this->addSuccessFlashMsg($document->getName(), 'updated');

			return $this->redirectToRoute('admin_bird_edit', array('id' => $document->getId()));
		}

		return array(
			'form' => $form->createView()
		);
	}

	/**
	 * @Route(path="/delete/{id}", name="admin_bird_delete")
	 * @param Bird $document
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 * @internal param string $route
	 * @internal param $id
	 */
	public function deleteAction(Bird $document) {
		$this->dm()->flush($document->setDelete(true));

		$this->addDangerFlashMsg($document->getName(), 'deleted');

		return $this->redirectToRoute('admin_bird_index');
	}

	private function getAutoCompleteList() {
		$birdRepo = $this->dm()->getRepository('AppBundle:Bird');

		return array(
			'kingdomList' => $birdRepo->getDistinctField('kingdom'),
			'phylumList'  => $birdRepo->getDistinctField('phylum'),
			'classList'   => $birdRepo->getDistinctField('class'),
			'familyList'  => $birdRepo->getDistinctField('family'),
			'genusList'   => $birdRepo->getDistinctField('genus'),
			'speciesList' => $birdRepo->getDistinctField('species')
		);
	}
}
