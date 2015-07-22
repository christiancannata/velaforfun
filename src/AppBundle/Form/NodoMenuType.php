<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NodoMenuType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome')
            ->add(
                'action',
                'choice',
                array('choices' => array('TOP' => 'TOP', '_BLANK' => '_BLANK'))
            )
            ->add('link')
            ->add('colore')
            ->add('isActive')
            ->add('ordering')
            ->add('menu')
            ->add(
                'callback',
                'hidden',
                array(
                    'data' => 'reload',
                    'mapped' => false,
                    'attr' => array("class" => "callback")
                )
            )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\NodoMenu'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_nodomenu';
    }
}
