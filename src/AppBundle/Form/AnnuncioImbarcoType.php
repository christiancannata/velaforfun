<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AnnuncioImbarcoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'tipoAnnuncio',
                'choice',
                array('choices' => array('CERCO' => 'Cerco', 'OFFRO' => 'Offro'))
            )
            ->add('titolo')
            ->add('telefono')
            ->add(
                'luogo',
                'choice',
                array(
                    'choices' => array(
                        'NORD_ITALIA' => 'Nord Italia',
                        'SUD_ITALIA' => 'Sud Italia',
                        'CENTRO' => 'Centro',
                        'ESTERO' => 'Estero'
                    )
                )
            )
            ->add(
                'tipo',
                'choice',
                array('choices' => array('CABINATO' => 'Cabinato', 'DERIVA' => 'Deriva', 'ALTRO' => 'Altro'))
            )
            ->add('localita')
            ->add('tempo')
            ->add(
                'ruoloRichiesto',
                'choice',
                array(
                    'choices' => array(
                        'PRODIERE' => 'Prodiere',
                        'UOMO_ALBERO' => 'Uomo Albero',
                        'PITMAN' => 'Pitman',
                        'GRINDER' => 'Grinder',
                        'TAILER' => 'Tailer',
                        'TATTICO' => 'Tattico',
                        'RANDISTA' => 'Randista',
                        'TIMONIERE' => 'Timoniere',
                        'CUOCO' => 'Cuoco',
                        'MOZZO_GENERICO' => 'Mozzo Generico',
                        'COMANDANTE' => 'Comandante',
                        'SECONDO' => 'Secondo',
                        'MOTORISTA' => 'Motorista',
                        'STEWARD' => 'Steward',
                        'HOSTESS' => 'Hostess'
                    )
                )
            )
            ->add(
                'costo',
                'choice',
                array('choices' => array('GRATIS' => 'Gratis', 'A_PAGAMENTO' => 'A pagamento', 'PAGATO' => 'Pagato'))
            )
            ->add('descrizione')
            ->add('save', 'submit', array('label' => 'Inserisci'));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\AnnuncioImbarco'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_annuncioimbarco';
    }
}
