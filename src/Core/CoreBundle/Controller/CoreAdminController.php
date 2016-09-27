<?php
namespace Core\CoreBundle\Controller;

use Core\UserBundle\Document\User;
use Core\UserBundle\Form\Type\AdminUserType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CoreAdminController
 * @package Core\CoreBundle\Controller
 */
abstract class CoreAdminController extends CoreController {
	/**
	 * @param string $type
	 * @param string $object
	 * @param string $action
	 */
	protected function addFlashMessage($type = 'info', $object = '', $action) {
		$message = sprintf('Document "%s" successfully %s!', $object, $action);

		$this->get('session')->getFlashBag()->add($type, $message);
	}

	protected function addInfoFlashMsg($object = '', $action) {
		$this->addFlashMessage('info', $object, $action);
	}

	protected function addSuccessFlashMsg($object = '', $action) {
		$this->addFlashMessage('success', $object, $action);
	}

	protected function addWarningFlashMsg($object = '', $action) {
		$this->addFlashMessage('warning', $object, $action);
	}

	protected function addDangerFlashMsg($object = '', $action) {
		$this->addFlashMessage('danger', $object, $action);
	}

	/**
	 * @return \Symfony\Component\Form\Form
	 */
	protected function createNewForm() {
		return $this->createForm(new AdminUserType(), new User(), array('action' => $this->generateUrl('admin_user_create')));
	}

	/**
	 * @param Request $request
	 *
	 * @return null|\Symfony\Component\Form\Form
	 */
	protected function handleCreateRequest(Request $request) {
		$form = $this->createNewForm();
		$form->handleRequest($request);
		if($form->isValid()) {
			return $form;
		}

		return null;
	}

	/**
	 * @param string $id
	 * @param string $action
	 *
	 * @return \Symfony\Component\Form\Form
	 */
	protected function createDeleteForm($id, $action) {
		return $this
			->createFormBuilder(array ('id' => $id))
			->setAction($action)
			->add('id', 'hidden')
			->getForm();
	}
}