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
            ->add(
                'luogoAttuale',
                'entity',
                array(
                    'label' => 'Dove hai la barca adesso?',
                    'class' => 'AppBundle:Porto',
                    'group_by' => 'regione',
                    'property' => 'nome',
                    'query_builder' => function (\Doctrine\ORM\EntityRepository $repo) {
                        $qb = $repo->createQueryBuilder('l')->orderBy('l.nome', 'ASC');
                        return $qb;
                    }
                )
            )
            ->
            add('luogoRicercato', 'choice', array("label" => "Dove cerchi un posto?",'choices'=>array('NORD_ITALIA_TIRRENO'=>'Nord Italia Tirreno','NORD_ITALIA_ADRIATICO'=>'Nord Italia Adriatico','CENTRO_ITALIA_TIRRENO'=>'Centro Italia Tirreno','CENTRO_ITALIA_ADRIATICO'=>'Centro Italia Adriatico','SUD_ITALIA_TIRRENO'=>'Sud Italia Tirreno','SUD_ITALIA_ADRIATICO'=>'Sud Italia Adriatico','SARDEGNA'=>'Sardegna','SICILIA'=>'Sicilia','ESTERO'=>'Estero')))
            ->add('tipo', 'choice', array("label" => "Cosa?",'choices'=>array("VELA"=>"Vela","MOTORE"=>"Motore","ALTRO"=>"Altro")))
            ->add(
                'tempo',
                null,
                array("label" => "Tempo (specifica se possibile le date e/o la durata e/o il periodo)")
            )
            ->add('lunghezza', null, array("label" => "Lunghezza (metri)"))
            ->add('descrizione', null, array("label" => "Note richiesta:"));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\AnnuncioScambioPosto'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_annuncioscambioposto';
    }
}
