<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AnnuncioScambioPostoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('telefono')
            ->add('luogoAttuale')
            ->add('luogoRicercato')
            ->add('tipo')
            ->add('tempo')
            ->add('lunghezza')
            ->add('descrizione')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\AnnuncioScambioPosto'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_annuncioscambioposto';
    }
}
