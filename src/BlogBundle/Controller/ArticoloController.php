<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticoloController extends Controller
{

    public function showAction($categoria,$permalink)
    {
        $em = $this->getDoctrine()->getEntityManager();


        $articolo = $em->getRepository('BlogBundle:Articolo')->findOneByPermalink($permalink);
        if (!$articolo) {
            throw $this->createNotFoundException('Unable to find Articolo.');
        }

        return $this->render('BlogBundle:Articolo:articolo.html.twig', array(
            'articolo'      => $articolo,
        ));
    }
}
