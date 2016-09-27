<?php
/**
 * Created by PhpStorm.
 * User: seth
 * Date: 11-Sep-16
 * Time: 11:02 AM
 */

namespace Core\UserBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		parent::buildForm($builder, $options);
		$builder
			->add('firstName')
			->add('lastName')
			->add('email','email', array(
				'required' => true,
			))
			->add('username', 'text', array(
				'required' => true
			))
			->add('password', RepeatedType::class, array (
				'type'            => PasswordType::class,
				'invalid_message' => 'Mismatch password',
				'required'        => true,
				'first_options'   => array ('label' => 'Password'),
				'second_options'  => array ('label' => 'Repeat Password')
			))
//			->add('profileImage', 'file')
		;
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array (
			'required'   => false,
			'data_class' => 'Core\UserBundle\Document\User'
		));
	}
}