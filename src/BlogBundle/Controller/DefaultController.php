<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Overlays\Animation;
use Ivory\GoogleMap\Overlays\Marker;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $repository = $this->getDoctrine()
            ->getRepository('BlogBundle:Articolo');

        $map = new Map();

        $porti = $this->getDoctrine()
            ->getRepository('AppBundle:Porto')->findAll();

        foreach($porti as $porto){
            $marker = new Marker();

// Configure your marker options
            $marker->setPrefixJavascriptVariable('marker_');
            $marker->setPosition($porto->getLatitudine(), $porto->getLongitudine(), true);
            $marker->setAnimation(Animation::DROP);

            $marker->setOption('clickable', false);
            $marker->setOption('flat', true);
            $marker->setOptions(array(
                'clickable' => false,
                'flat'      => true,
            ));
            $map->addMarker($marker);

        }



        return $this->render('BlogBundle:Default:index.html.twig', array('name' => $name,''));
    }
}
