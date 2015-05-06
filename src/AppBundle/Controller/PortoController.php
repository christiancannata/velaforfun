<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Porto;
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


class PortoController extends BaseController
{

    protected $entity="Porto";

    /**
     * @Route( "crea", name="create_porto" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new PortoType());
    }

    /**
     * @Route( "modifica/{id}", name="modifica_porto" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new PortoType(),$id,"Porto");
    }


    /**
     * @Route( "list", name="list_porto" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }



    /**
     * @Route( "elimina/{id}", name="delete_porto" )
     * @Template()
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }


    /**
     * @Route("/", name="porti_italia")
     */
    public function portiItaliaAction()
    {

        $map = new Map();
        $map->setAsync(true);
        $map->setLanguage("ita");
        $porti = $this->getDoctrine()
            ->getRepository('AppBundle:Porto')->findAll();

        foreach ($porti as $porto) {
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

        }

        $mapHelper = new MapHelper();

        return $this->render('AppBundle:Porto:porti.html.twig', array("mapHelper" => $mapHelper, "map" => $map));
    }


    /**
     * @Route("/{permalink}", name="dettaglio_porto")
     */
    public function dettagliPortoAction($permalink)
    {

        $client=$this->container->get('weather.client');


        $map = new Map();
        $map->setAsync(true);
        $map->setLanguage("ita");
        $porto = $this->getDoctrine()
            ->getRepository('AppBundle:Porto')->findOneByPermalink($permalink);
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

        $request = $client->get('/data/2.5/weather?lat='.$porto->getLatitudine().'&lon='.$porto->getLongitudine().'&APPID=8704a88837e9eabcf7b50de51728a0c0');
        $response = $client->send($request);
        $weather=json_decode($response->getBody(true));

        var_dump($weather);

        return $this->render('AppBundle:Porto:dettagliPorto.html.twig', array("mapHelper" => $mapHelper, "map" => $map));
    }
}
