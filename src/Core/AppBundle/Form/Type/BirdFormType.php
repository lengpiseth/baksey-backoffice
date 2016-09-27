<?php
/**
 * Created by PhpStorm.
 * User: seth
 * Date: 4/11/16
 * Time: 9:38 PM
 */

namespace Core\AppBundle\Form\Type;


use Core\CoreBundle\Form\Type\CoreFormType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BirdFormType extends CoreFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('name')
            ->add('knownAs')
            ->add('scientificName')
            ->add('description', 'textarea')
            ->add('nameInKhmer')
            ->add('nameInKhmer')
            ->add('knownAsInKhmer')
            ->add('scientificNameInKhmer')
            ->add('descriptionInKhmer', 'textarea')

            ->add('kingdom', null, [
                'attr'      => [
                    'class'    => 'autocomplete'
                ]
            ])
            ->add('phylum')
            ->add('class')
            ->add('family')
            ->add('genus')
            ->add('species')

            ->add('photos', CollectionType::class, array(
                'entry_type'        => 'text',
                'entry_options'     => array(
                    'attr'  => array('class' => 'photos-box')
                ),
                'allow_add'         => true,
                'allow_delete'      => true,
                'empty_data'        => 'collection_here',
                'by_reference'      => false
            ))
            ->remove('active')
            ->remove('delete')
            ->remove('order')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => 'Core\AppBundle\Document\Bird',
            'required'      => false
        ));
    }

    public function getName()
    {
        return self::class;
    }
}