<?php
/**
 * Created by PhpStorm.
 * User: seth
 * Date: 4/21/16
 * Time: 8:56 PM
 */

namespace Core\RestBundle\Controller;

use Core\AppBundle\Document\Bird;
use Core\AppBundle\Form\Type\BirdFormType;
use Core\CoreBundle\CoreApp\JsonWrapper;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApiBirdController
 * @package Core\RestBundle\Controller
 */
class ApiBirdController extends CoreRestController
{
    protected $serviceName = 'core.app.bird_service';

    /**
     * @ApiDoc(
     *     section="Bird",
     *     resource=true,
     *     input="Core\AppBundle\Form\Type\BirdFormType"
     * )
     * @Route(path="/", methods={"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postBirdAction(Request $request)
    {
        $result = JsonWrapper::singleResult('error');
        $bird = new Bird();
        $form = $this->createForm(new BirdFormType(), $bird, array('csrf_protection' => false));
        $form->handleRequest($request);

        if($form->isValid()) {
            $this->dm()->persist($bird);
            $this->dm()->flush();

            $result['status'] = 'success';
            $result['data'] = $bird;
        }
        else {
            $result['errors'] = $form->getErrors()['errors'];
        }

        return $this->handleView($this->view($result));
    }

    /**
     * Get list of birds <br/>
     * <strong>Expected result:</strong> <br/>
     * {
     * "status": "success",
     * "data": [
     * {
     * "id": "570f3c27aa008beb278b4567",
     * "author": {
     * "id": "5709ed5baa008b260f8b4567",
     * "username": "admin",
     * "usernameCanonical": "admin",
     * "email": "admin@work.com",
     * "emailCanonical": "admin@work.com",
     * "enabled": true,
     * "salt": "90snsohwwco4sg8swgk0gk4g00occsk",
     * "password": "$2y$13$90snsohwwco4sg8swgk0gehAwqIUHB0CSAtJh0AoIja1C4IKtkTJi",
     * "lastLogin": "1461253010",
     * "locked": false,
     * "expired": false,
     * "roles": [
     * "ROLE_SUPER_ADMIN"
     * ]
     * },
     * "name": "Snowy Owl",
     * "createDate": 1460616231977,
     * "updateDate": 1460616231977,
     * "delete": false,
     * "active": true,
     * "scientificName": "Athene noctua",
     * "thumbnails": [],
     * "photos": [],
     * "kingdom": "Animalia",
     * "phylum": "Chordata",
     * "class": "Dinosauria",
     * "family": "Tytonidae",
     * "genus": "Bubo",
     * "species": "Taxa"
     * }
     * ],
     * "nextPage": true
     * }
     * @ApiDoc(
     *     resource=true,
     *     section="Bird"
     * )
     * @Route(path="/", methods={"GET"})
     * @QueryParam(name="page", nullable=true, default="1")
     * @QueryParam(name="count", nullable=true, default="20")
     * @QueryParam(name="sort[name]", nullable=true, default="asc", description="asc = Ascending; desc = Descending")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param QueryParam $param
     * @internal param ParamFetcher $fetcher
     */
    public function getIndexAction(Request $request)
    {
        $page   = $request->query->getInt('page', 1);
        $count  = $request->query->getInt('count', 20);
        $sort   = $request->query->get('sort', []);
        $service= $this->get($this->serviceName);

        $birds = $service->getBirdList($page, $count, $sort);

        $view = $this->view($birds);

        return $this->handleView($view);
    }

    /**
     * Get a bird detail
     * <br/><strong>Expected result:</strong><br/>
     *
     * {
    "id": "5716e3b83776b914903b65b4",
    "name": "Chinese Francolin",
    "scientificName": " Francolinus pintadeanus",
    "nameInKhmer": "ឈ្មោះខ្មែរ",
    "thumbnails": [],
    "photos": [],
    "kingdom": "Animalia",
    "phylum": "Chordate",
    "class": "Aves"
    }
     *
     * @ApiDoc(
     *     section="Bird",
     *     resource=true
     * )
     * @Route(path="/{id}", methods={"GET"})
     * @param Bird $bird
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getBirdAction(Bird $bird)
    {
        return $this->handleView($this->view($bird));
    }

    /**
     * Delete a bird
     * <br/><strong>Expected result:</strong><br/>
     *
     *{
    "result": "<boolean>"
     * }
     *
     * @ApiDoc(
     *     section="Bird",
     *     resource=true
     * )
     * @Route(path="/{id}", methods={"DELETE"})
     * @param Bird $bird
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteBirdAction(Bird $bird)
    {
        $bird->setDelete(true);
        $this->dm()->flush($bird);

        return $this->handleView($this->view(array('result' => $bird->getDelete())));
    }

    /**
     * @ApiDoc(
     *     section="Bird",
     *     resource=true,
     *     tags={"development" : "orange"},
     *     input="Core\AppBundle\Form\Type\BirdFormType"
     * )
     * @Route(path="/{id}", methods={"PATCH"})
     * @param Request $request
     * @param Bird $bird
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function patchBirdAction(Request $request, Bird $bird)
    {
        $result = JsonWrapper::singleResult('error');
        $form = $this->createForm(new BirdFormType(), $bird, array('csrf_protection' => false));
        $form->handleRequest($request);

        if($form->isValid()) {
            $this->dm()->persist($bird);
            $this->dm()->flush();

            $result['status'] = 'success';
            $result['data'] = $bird;
        }
        else {
            $result['errors'] = $form->getErrors()['errors'];
        }

        return $this->handleView($this->view($result));
    }
}