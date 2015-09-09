<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FotoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('profilePictureFile',null,array("label"=>"Carica una foto","attr"=>array("required"=>"required")))
            ->add('nome',null,array("attr"=>array("required"=>"required")))
            ->add('inEvidenza')
            ->add('autore')
            ->add('galleria',null,array("label"=>"Categoria"),array("attr"=>array("required"=>"required")))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Foto'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_foto';
    }
}
