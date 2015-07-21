<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		// add your custom field
		$builder->add('nome');
		$builder->add('cognome');
		$builder->add('dataNascita','birthday',array(  'format' => 'd-M-y','attr'=> array('class'=>'datetimepicker')));
        $builder->add('profilePictureFile',null, array('label' => 'Avatar'));
        $builder->add('firma',null, array('label' => 'La tua firma'));
	}

	public function getParent()
	{
		return 'fos_user_registration';
	}

	public function getName()
	{
		return 'app_user_registration';
	}


}