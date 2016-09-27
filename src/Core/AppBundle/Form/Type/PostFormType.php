<?php
/**
 * Created by PhpStorm.
 * User: seth
 * Date: 4/11/16
 * Time: 9:38 PM
 */

namespace Core\AppBundle\Form\Type;


use Core\CoreBundle\Form\Type\CoreFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostFormType extends CoreFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('name', 'text', array(
                'label'     => 'Title'
            ))
            ->add('content', 'textarea')
            ->add('featureImage')
            ->remove('active')
            ->remove('delete')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
//        parent::configureOptions($resolver);
        $resolver->setDefaults(array(
            'data_class'    => 'Core\AppBundle\Document\Post',
            'required'      => false
        ));
    }

    public function getName()
    {
        return self::class;
    }
}