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

        $articoli=$repository->findAll();


        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Segnale');

        $segnali=$repository->findAll();




        return $this->render('default/index.html.twig',array("articoli"=>$articoli,"segnali"=>$segnali));
    }
}
