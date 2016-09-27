<?php
/**
 * Created by PhpStorm.
 * User: seth
 * Date: 4/11/16
 * Time: 9:39 PM
 */

namespace Core\CoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class CoreFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('active')
            ->add('delete')
            ->add('order')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Core\CoreBundle\Document\CoreDocument'
        ));
    }

    public function getName()
    {
        return self::class;
    }
}