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
            ->add('permalink')
            ->add('testo')
            ->add('immagine')
            ->add('tags')
            ->add('timestamp')
            ->add('lastUpdateTimestamp')
            ->add('idComunicato')
            ->add('autore')
            ->add('categoria')
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
