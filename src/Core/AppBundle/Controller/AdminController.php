<?php
namespace Core\AppBundle\Controller;

use Core\CoreBundle\Controller\CoreAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class AdminController
 * @package Core\AppBundle\Controller
 * @Route("/admin")
 */
class AdminController extends CoreAdminController
{
    /**
     * @Route(path="/", name="admin_dashboard")
     * @Template(":AppBundle:dashboard.html.twig")
     */
    public function dashboardAction()
    {
        return array();
    }


}