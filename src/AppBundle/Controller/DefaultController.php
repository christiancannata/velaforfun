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
     * @Route("/profilo/tuoi-annunci", name="tuoi_annunci")
     */
    public function tuoiAnnunciAction()
    {

        $annunciImbarco= $repository = $this->getDoctrine()
            ->getRepository('AppBundle:AnnuncioImbarco')->findByUtente($this->getUser());

        $annunciScambio= $repository = $this->getDoctrine()
            ->getRepository('AppBundle:AnnuncioScambioPosto')->findByUtente($this->getUser());

        return $this->render('default/tuoi-annunci.html.twig', array("annunciImbarco"=>$annunciImbarco,"annunciScambio"=>$annunciScambio));
    }


    /**
     * @Route("/contatti", name="contatti")
     */
    public function contattiAction()
    {

        return $this->render('default/contatti.html.twig', array());
    }


    /**
     * @Route("/faq", name="contatti")
     */
    public function faqAction()
    {

        return $this->render('default/faq.html.twig', array());
    }
}
