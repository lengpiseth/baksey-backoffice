<?php
namespace Core\CoreBundle\Controller;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class CoreController extends Controller
{
    protected $bundleName;
    protected $documentName;

    public function __construct($bundleName = '', $documentName = '')
    {
        $this->bundleName       = $bundleName;
        $this->documentName     = $bundleName.':'.$documentName;
    }

	/**
	 * @param string $name
	 *
	 * @return \Doctrine\ODM\MongoDB\DocumentManager|object
	 */
    protected function dm($name = 'doctrine_mongodb.odm.default_document_manager')
    {
        return $this->get($name);
    }

    /**
     * @param $object
     * @param string $format
     * @param array $groups
     * @return Serializer
     */
    protected function serialize($object, $format = JsonEncoder::FORMAT, array $groups = array())
    {
        $classMetadataFactory   = (count($groups) > 0) ? new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader())) : null;
        $encoders               = array(new XmlEncoder(), new JsonEncoder());
        $normalizers            = array(new ObjectNormalizer($classMetadataFactory));
        $serializer             = new Serializer($normalizers, $encoders);

        return $serializer->serialize($object, $format, array('groups' => $groups));
    }

    /**
     * @return \Knp\Component\Pager\Paginator
     */
    protected function paginator()
    {
        return $this->get('knp_paginator');
    }
}