<?php
/**
 * Created by PhpStorm.
 * User: seth
 * Date: 4/11/16
 * Time: 9:04 PM
 */

namespace Core\AppBundle\Controller;


use Core\AppBundle\Document\Post;
use Core\AppBundle\Form\Type\PostFormType;
use Core\CoreBundle\Controller\CoreAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminPostController
 * @package Core\AppBundle\Controller
 * @Route("/admin/post")
 */
class AdminPostController extends CoreAdminController
{
    public function __construct()
    {
        parent::__construct('AppBundle', 'Post');
    }

    /**
     * @Route(path="/", name="admin_post_index")
     * @Template(":AppBundle:post/index.html.twig")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $page = $request->query->getInt('page', 0);

        return array(
            'page' => $page
        );
    }

    /**
     * @Route(path="/show/{id}", name="admin_post_show")
     * @Template(":AppBundle:post/show.html.twig")
     * @param Post $document
     * @return array
     */
    public function showAction(Post $document)
    {
        return array(
            'document' => $document
        );
    }

    /**
     * @Route(path="/new", name="admin_post_new")
     * @Template(":AppBundle:post/new.html.twig")
     * @return array
     */
    public function newAction()
    {
        $form = $this->createForm(PostFormType::class, new Post(), array(
            'action' => $this->generateUrl('admin_post_create')
        ));

        return array(
            'form'      => $form->createView()
        );
    }

    /**
     * @Route(path="/create", name="admin_post_create")
     * @Method({"POST"})
     * @Template(":AppBundle:post/new.html.twig")
     * @param Request $request
     * @return array
     */
    public function createAction(Request $request)
    {
        $document = new Post();

        $form = $this->createForm(PostFormType::class, $document, array(
            'action' => $this->generateUrl('admin_post_create')
        ));
        $form->handleRequest($request);

        if($form->isValid()) {
            $document->setAuthor($this->get('security.token_storage')->getToken()->getUser());
            $this->dm()->persist($document);
            $this->dm()->flush();

            $this->addSuccessFlashMsg($document->getName(), 'saved');

            return $this->redirectToRoute('admin_post_show', array('id' => $document->getId()));
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(path="/edit/{id}", name="admin_post_edit")
     * @Template(":AppBundle:post/edit.html.twig")
     * @param Post $document
     * @return array
     */
    public function editAction(Post $document)
    {
        $form = $this->createForm(PostFormType::class, $document, array(
            'action' => $this->generateUrl('admin_post_update', array('id' => $document->getId()))
        ));

        $deleteForm = $this->createDeleteForm($document->getId(), $this->generateUrl('admin_post_delete', array('id' => $document->getId())));

        return array(
            'form'          => $form->createView(),
            'deleteForm'    => $deleteForm->createView()
        );
    }

    /**
     * @Route(path="/update/{id}", name="admin_post_update")
     * @Method({"POST"})
     * @Template(":AppBundle:post/edit.html.twig")
     * @param Request $request
     * @param Post $document
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @internal param Request $request
     */
    public function updateAction(Request $request, Post $document)
    {
        $form = $this->createForm(PostFormType::class, $document);
        $form->handleRequest($request);

        if($form->isValid()) {
            $this->dm()->persist($document);
            $this->dm()->flush();

            $this->addSuccessFlashMsg($document->getName(), 'updated');

            return $this->redirectToRoute('admin_post_edit', array('id' => $document->getId()));
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route(path="/delete/{id}", name="admin_post_delete")
     * @param Post $document
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @internal param string $route
     * @internal param $id
     */
    public function deleteAction(Post $document)
    {
        $this->dm()->flush($document->setDelete(true));

        $this->addDangerFlashMsg($document->getName(), 'deleted');

        return $this->redirectToRoute('admin_post_index');
    }
}