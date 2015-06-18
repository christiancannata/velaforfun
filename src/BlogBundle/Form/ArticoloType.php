<?php

namespace BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticoloType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titolo')
            ->add('stato','choice',array('choices'=>array('ATTIVO'=>'ATTIVO','BOZZA'=>'BOZZA','DISATTIVO'=>'DISATTIVO')))
            ->add('sottotitolo')
            ->add('testo',null, array('attr' => array(
                'style' => 'height:400px'
            )))
            ->add('profilePictureFile',null,array("label"=>"Immagine articolo"))
            ->add('tags')
            ->add('idComunicato')
            ->add('autore')
            ->add('categoria')
            ->add('permalink')
            ->add('titoloCorrelato')
            ->add('testoCorrelato')
            ->add('linkCorrelato')
            ->add('immagineCorrelata')
            ->add('gallery')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlogBundle\Entity\Articolo'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'blogbundle_articolo';
    }
}
