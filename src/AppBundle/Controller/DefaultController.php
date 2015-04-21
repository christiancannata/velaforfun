<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Overlays\Animation;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Helper\MapHelper;

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




        $map = new Map();
        $map->setAsync(true);
        $map->setLanguage("ita");
        $porti = $this->getDoctrine()
            ->getRepository('AppBundle:Porto')->findAll();

        foreach($porti as $porto){
            $marker = new Marker();

// Configure your marker options
            $marker->setPrefixJavascriptVariable('marker_');
            $marker->setPosition($porto->getLatitudine(), $porto->getLongitudine(), true);
            $marker->setAnimation(Animation::DROP);
            $marker->setOptions(array(
                'clickable' => false,
                'flat'      => true,
            ));
            $map->addMarker($marker);

        }

        $mapHelper = new MapHelper();

        return $this->render('default/index.html.twig',array("articoli"=>$articoli,"mapHelper"=>$mapHelper,"map"=>$map));
    }
}
