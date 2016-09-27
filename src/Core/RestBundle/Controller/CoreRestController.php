<?php
/**
 * Created by PhpStorm.
 * User: seth
 * Date: 4/21/16
 * Time: 8:56 PM
 */

namespace Core\RestBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;

abstract class CoreRestController extends FOSRestController
{
    protected $serviceName;

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager|object
     */
    protected function dm()
    {
        return $this->get('doctrine.odm.mongodb.document_manager');
    }
}