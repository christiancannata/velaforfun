<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {

        $repository = $this->getDoctrine()
            ->getRepository('BlogBundle:Articolo');

        $articoli=$repository->findByStato("ATTIVO",array('id' => 'desc'));




        return $this->render('default/index.html.twig',array("articoli"=>$articoli));
    }



    /**
     * @Route("/chi-siamo", name="chi_siamo")
     */
    public function chiSiamoAction()
    {

        return $this->render('default/chi-siamo.html.twig', array());
    }

    /**
     * @Route("/contatti", name="contatti")
     */
    public function contattiAction()
    {

        return $this->render('default/contatti.html.twig', array());
    }
}
