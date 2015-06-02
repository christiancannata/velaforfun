<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;

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
            ->getRepository('BlogBundle:Articolo')->findAll(array("id"=>"desc"), 5);

        return $this->render('BlogBundle:Default:index.html.twig', array('categorie' => $categorie,'ultimiArticoli'=>$ultimiArticoli));
    }
}
