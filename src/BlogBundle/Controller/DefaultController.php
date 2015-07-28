<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends BaseController
{





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


    /**
     * @Route("/json-all", name="archivio_completo_json")
     */
    public function getArchivioJsonAction( Request $r)
    {
        $em = $this->getDoctrine()->getEntityManager();


        $articoli = $em->getRepository('BlogBundle:Articolo')->findBy(
            array('stato' => "ATTIVO"),
            array('lastUpdateTimestamp' => 'desc')
        );
        $array=[];
        foreach($articoli as $articolo){
            $array[]=array(
            "permalink"=>$articolo->getTitolo()."|/archivio/".$articolo->getCategoria()->getPermalink()."/".$articolo->getPermalink()."|/var/www/immagini/articoli/".$articolo->getImmagine()."|".strip_tags($articolo->getSottotitolo()),
                "name"=>$articolo->getId()." - ". $articolo->getTitolo()." - ".$articolo->getTimestamp()->format("d-m-Y H:i")
            );
        }

        return new JsonResponse($array);

    }
}
