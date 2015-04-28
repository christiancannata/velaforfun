<?php

namespace AppBundle\Controller;

use AppBundle\Form\PortoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Overlays\Animation;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Helper\MapHelper;


class NodoController extends BaseController
{
    /**
     * @Route( "crea", name="create_post" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new PortoType());
    }


    /**
     * @Route("/", name="nodi")
     */
    public function nodiAction()
    {

        $map = new Map();
        $map->setAsync(true);
        $map->setLanguage("ita");
        $nodi = $this->getDoctrine()
            ->getRepository('AppBundle:Nodo')->findAll();


        return $this->render('AppBundle:Nodo:nodi.html.twig', array("nodi" => $nodi));
    }


    /**
     * @Route("/{permalink}", name="dettaglio_nodo")
     */
    public function dettagliNodoAction($permalink)
    {

        $map = new Map();
        $map->setAsync(true);
        $map->setLanguage("ita");
        $porto = $this->getDoctrine()
            ->getRepository('AppBundle:Nodo')->findOneByPermalink($permalink);
        if(!$porto){


            throw $this->createNotFoundException('Unable to find Articolo.');
        }

        $marker = new Marker();

// Configure your marker options
        $marker->setPrefixJavascriptVariable('marker_');
        $marker->setPosition($porto->getLatitudine(), $porto->getLongitudine(), true);
        $marker->setAnimation(Animation::DROP);
        $marker->setOptions(
            array(
                'clickable' => false,
                'flat' => true,
            )
        );
        $map->addMarker($marker);


        $mapHelper = new MapHelper();

        return $this->render('AppBundle:Porto:dettagliPorto.html.twig', array("mapHelper" => $mapHelper, "map" => $map));
    }
}
