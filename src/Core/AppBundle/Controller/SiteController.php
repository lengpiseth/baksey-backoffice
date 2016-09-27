<?php

namespace Core\AppBundle\Controller;

use Core\CoreBundle\Controller\CoreController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SiteController extends CoreController
{
    /**
     * @Route(path="/", name="homepage")
     * @Template(":default:index.html.twig")
     * @return array
     */
    public function indexAction()
    {
        return array(
            'base_dir' => 'src'
        );
    }
}
