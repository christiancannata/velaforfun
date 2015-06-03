<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PortoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome')
            ->add('regione')
            ->add('latitudine')
            ->add('longitudine')
            ->add('datiOver')
            ->add('postiTotale')
            ->add('postiTransito')
            ->add('email')

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Porto'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_porto';
    }
}
