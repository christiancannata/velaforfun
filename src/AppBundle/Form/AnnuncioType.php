<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AnnuncioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('referente',null,array("label"=>"Nome e Cognome"))
            ->add('titolo')
            ->add('email')
            ->add('telefono')
            ->add(
                'tipo',
                'choice',
                array('choices' => array('COMPRO' => 'COMPRO', 'VENDO' => 'VENDO'))
            )
            ->add('descrizione','textarea');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Annuncio'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_annuncio';
    }
}
