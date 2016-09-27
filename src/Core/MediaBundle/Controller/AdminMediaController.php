<?php
/**
 * Created by PhpStorm.
 * User: seth
 * Date: 4/12/16
 * Time: 11:11 PM
 */

namespace Core\MediaBundle\Controller;


use Core\CoreBundle\Controller\CoreAdminController;
use Core\MediaBundle\Document\Media;
use Core\MediaBundle\Form\Type\MediaDropZoneType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminMediaController
 * @package Core\MediaBundle\Controller
 * @Route("/admin/media")
 */
class AdminMediaController extends CoreAdminController
{
    /**
     * @Route(path="/", name="admin_media_index")
     * @Template(":MediaBundle:index.html.twig")
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        $page   = $request->query->getInt('page', 0);
        $sortBy = $request->query->get('sortby', 'uploadDate');
        $order  = $request->query->getInt('order', 1);

        $form = $this->createForm(new MediaDropZoneType(), null, array(
            'action' => $this->generateUrl('admin_media_create'),
        ));

        $form->remove('file')->remove('submit');

        return array(
            'page'      => $page,
            'sortBy'    => $sortBy,
            'order'     => $order,
            'form'      => $form->createView(),
            'uploadMaxFilesize' => ini_get('upload_max_filesize')
        );
    }

    /**
     * @ApiDoc(
     *     resource=true
     * )
     * @Route(path="/library", name="admin_media_library")
     * @Template(":MediaBundle:library.html.twig")
     * @param Request $request
     * @return array
     */
    public function libraryAction(Request $request)
    {
        $page   = $request->query->getInt('page', 1);
        $sortBy = $request->query->get('sortby', 'uploadDate');
        $order  = $request->query->getInt('order', -1);
        $count  = $request->query->getInt('count', 50);

        $medias = $this->dm()->getRepository('MediaBundle:Media')->getLibrary(null, $page, $count, array($sortBy => $order));

        if($request->isXmlHttpRequest()) {
            $jsonResponse = new Response($this->serialize($medias, 'json', array('admin')));
            $jsonResponse->headers->set('Content-Type', 'application/json');
            return $jsonResponse;
        }

        return array(
            'medias' => $medias
        );
    }

    /**
     * @Route(path="/new", name="admin_media_new")
     * @Template(":MediaBundle:new.html.twig")
     * @param Request $request
     * @return array
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(new MediaDropZoneType(), null, array(
            'action' => $this->generateUrl('admin_media_create'),
        ));

        $form->remove('file')->remove('submit');

        return array(
            'form'              => $form->createView(),
            'uploadMaxFilesize' => ini_get('upload_max_filesize')
        );
    }

    /**
     * @Route(path="/create", name="admin_media_create")
     * @Method("POST")
     * @param Request $request
     * @return array
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(new MediaDropZoneType(), null, array(
            'action' => $this->generateUrl('admin_media_create')
        ));
        $form->handleRequest($request);

        if($form->isValid()) {
            /** @var UploadedFile $image */
            $image = $request->files->get('file');

            $document = new Media();
            $document
                ->setFile($image->getPathname())
                ->setFilename($image->getClientOriginalName())
                ->setExtension($image->getClientOriginalExtension())
                ->setMimeType($image->getClientMimeType());
            $this->dm()->persist($document);
            $this->dm()->flush();

            if($document->getId()) {
                $mediaParams = $this->getParameter('media');

                $this->get('core.media.service')->generateThumbnail($document, $mediaParams['thumbnail']['size']);

                return new JsonResponse(array(
                    'result'        => true,
                    'id'            => $document->getId(),
                    'thumbnailUrl'  => $this->generateUrl('media_view', array('id' => $document->getId()))
                ));
            }
        }

        return new JsonResponse(array('result' => false));
    }

    /**
     * @Route(path="/delete/{id}", defaults={"id"=0}, name="admin_media_delete")
     * @Method({"POST"})
     * @param Media $media
     * @return JsonResponse
     */
    public function deleteAction(Media $media)
    {
        $this->dm()->remove($media);
        $this->dm()->flush();

        return new JsonResponse(array('result' => true));
    }
}