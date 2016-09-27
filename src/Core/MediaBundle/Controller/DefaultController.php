<?php
namespace Core\MediaBundle\Controller;


use Core\CoreBundle\Controller\CoreController;
use Core\MediaBundle\Document\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package Core\MediaBundle\Controller
 * @Route("/media")
 */
class DefaultController extends CoreController
{

    /**
     * @Route(path="/download/{id}", name="media_download")
     * @param Media $media
     * @return Response
     */
    public function downloadAction(Media $media)
    {
        $response = new Response();
        $response->headers->set('Content-Description', 'File Transfer');
        $response->headers->set('Content-Type', $media->getMimeType());
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $media->getFilename()));
        $response->headers->set('Content-Transfer-Encoding', 'binary');
        $response->headers->set('Content-Length', "{$media->getSize()}");
        $response->setContent($media->getFile()->getBytes());

        return $response;
    }

    /**
     * @Route(path="/view/{id}", name="media_view")
     * @param Request $request
     * @param $id
     * @return Response
     * @throws \Doctrine\ODM\MongoDB\LockException* @internal param Media $image
     * @internal param $id
     */
    public function viewAction(Request $request, $id)
    {
        $file           = null;
        $mediaParams    = $this->getParameter('media');
        $fallbackFile   = $mediaParams['fallBackImage'];

        $media = $this->dm()->getRepository('MediaBundle:Media')->find($id);

        if(null !== $media) {
            $size   = $request->query->getInt('s');

            $file = ($this->get('core.media.service')->generateThumbnail($media, $size));
        }
        else {
            $file = ($fallbackFile);
        }

        $response = new BinaryFileResponse($file, 200, array(), true, null, true);

        return $response;
    }
}