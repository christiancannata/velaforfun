<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PaginaStaticaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome')
            ->add('permalink')
            ->add('titolo')
            ->add('descrizione')
            ->add('content')
            ->add('immagineCorrelataArticolo')
            ->add('titoloCorrelato')
            ->add('testoCorrelato')
            ->add('linkCorrelato')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\PaginaStatica'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_paginastatica';
    }
}
