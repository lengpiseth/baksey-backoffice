<?php
/**
 * Created by PhpStorm.
 * User: seth
 * Date: 30-Jun-16
 * Time: 7:54 PM
 */

namespace Core\CoreBundle\Service;


use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class CoreService
{
    protected $container;
    protected $repositoryName;

    public function __construct(ContainerInterface $containerInterface, $repositoryName)
    {
        $this->container = $containerInterface;
        $this->repositoryName = $repositoryName;
    }
    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager|object
     * @throws \Throwable
     */
    protected function dm()
    {
        return $this->container->get('doctrine.odm.mongodb.document_manager');
    }

    /**
     * @return \Core\AppBundle\Repository\BirdRepository
     */
    protected function repository()
    {
        return $this->dm()->getRepository($this->repositoryName);
    }
}