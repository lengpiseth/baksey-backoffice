<?php
namespace Core\UserBundle\Controller;


use Core\CoreBundle\Controller\CoreAdminController;
use Core\UserBundle\Document\User;
use Core\UserBundle\Form\Type\AdminUserType;
use Core\UserBundle\Form\Type\UserProfileFormType;
use Doctrine\ODM\MongoDB\DocumentNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminUserController
 * @package Core\UserBundle\Controller
 * @Route("/admin/user")
 */
class AdminUserController extends CoreAdminController {
	public function __construct() {
		parent::__construct('UserBundle', 'User');
	}

	/**
	 * @Route(path="/", name="admin_user_index")
	 * @return array
	 * @internal param Request $request
	 */
	public function indexAction() {
		return array ();
	}

	/**
	 * @Route(path="/profile/{id}", name="admin_user_profile")
	 * @Template(":UserBundle:admin-profile.html.twig")
	 * @param $id
	 *
	 * @return array
	 * @throws DocumentNotFoundException
	 * @throws \Doctrine\ODM\MongoDB\LockException
	 * @internal param Request $request
	 */
	public function profileAction($id = null) {
		$user = null;

		if ($id) {
			$user = $this->dm()->getRepository('UserBundle:User')->find($id);
		} else if (null == $id) {
			$user = $this->get('security.token_storage')->getToken()->getUser();
		}

		if (null == $user) {
			throw new DocumentNotFoundException(sprintf("User with ID '%s' does not exist", $id));
		}

		$form = $this->createForm(new UserProfileFormType(), $user, array (
			'method' => 'POST',
			'action' => $this->generateUrl('admin_user_update', array ('id' => $user->getId()))
		));

		return array (
			'form' => $form->createView()
		);
	}

	/**
	 * @Route(path="/update/{id}", name="admin_user_update")
	 * @Method({"POST"})
	 * @param Request $request
	 * @param User $user
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function updateProfileAction(Request $request, User $user) {
		$form = $this->createForm(new UserProfileFormType(), $user);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$this->dm()->persist($user);
			$this->dm()->flush();
		}

		return $this->redirectToRoute('admin_user_profile', array ('id' => $user->getId()));
	}

	/**
	 * @Route(path="/new", name="admin_user_new")
	 * @Method({"GET"})
	 * @Template(":UserBundle:admin-new.html.twig")
	 */
	public function newUserAction() {
		$form = $this->createNewForm();

		return array (
			'form' => $form->createView()
		);
	}

	/**
	 * @Route(path="/create", name="admin_user_create")
	 * @Method({"POST"})
	 * @Template(":UserBundle:admin-new.html.twig")
	 * @param Request $request
	 *
	 * @return array|RedirectResponse
	 */
	public function createUserAction(Request $request) {
		$form = $this->createForm(new AdminUserType());
		$form->handleRequest($request);

		if ($form->isValid()) {
			$um = $this->get('fos_user.user_manager');
			/** @var User $user */
			$user = $um->createUser();
			$user
				->setFirstName($form->get('firstName')->getData())
				->setLastName($form->get('lastName')->getData())
				->setEmail($form->get('email')->getData())
				->setUsername($form->get('username')->getData())
				->setPlainPassword($form->get('password')->getData())
				->setEnabled(true)->addRole('ROLE_SUPER_ADMIN');

			$um->updateUser($user);

			$this->dm()->flush();
			$this->addSuccessFlashMsg($user->getEmailCanonical(), 'created');

			return new RedirectResponse($this->generateUrl('admin_user_profile', array('id' => $user->getId())));
		}

		return array(
			'form' => $form->createView()
		);
	}
}