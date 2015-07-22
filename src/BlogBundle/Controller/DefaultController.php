<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BaseController
{


    /**
     * @Route("/", name="homepage_archivio")
     */
    public function indexAction()
    {
        $categorie = $this->getDoctrine()
            ->getRepository('BlogBundle:Categoria')->findAll();

        $ultimiArticoli = $this->getDoctrine()
            ->getRepository('BlogBundle:Articolo')->findAll(array("id"=>"desc"), 10);

        return $this->render('BlogBundle:Default:index.html.twig', array('categorie' => $categorie,'articoli'=>$ultimiArticoli));
    }


    /**
     * @Route("/json", name="archivio_json")
     */
    public function getArticoliJsonAction( Request $r)
    {
        $em = $this->getDoctrine()->getEntityManager();


        $articoli = $em->getRepository('BlogBundle:Articolo')->findBy(
            array('stato' => "ATTIVO"),
            array('lastUpdateTimestamp' => 'desc'),
            3,
            $r->get('offset')
        );


        return $this->render(
            'BlogBundle:Categoria:articoli-ajax.html.twig',
            array(
                'articoli' => $articoli,
            )
        );

    }
}
