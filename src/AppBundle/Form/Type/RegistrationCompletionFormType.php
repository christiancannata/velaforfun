<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationCompletionFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		// add your custom field
		$builder->add('nome');
		$builder->add('cognome');
        $builder->add('username');
        $builder->add('dataNascita','string');
        $builder->add('roles', 'choice', array(
            'choices' => array(
                'ROLE_USER' => 'Utente semplice',
                'ROLE_MODERATOR' => 'Moderatore',
                'ROLE_ADMIN' => 'Admin',
                'ROLE_SUPER_ADMIN' => 'Super Admin'
            ),
            'expanded' => false,
            'multiple' => true,
            'required' => true
        ));
	}

	public function getName()
	{
		return 'app_user_registration';
	}


}