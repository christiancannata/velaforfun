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
     * @Route( "geojson_list", name="geojson_list_porto" )
     * @Template()
     */
    public function geoJsonlistAction(Request $request)
    {

        $json = array();
        $res = $this->cJsonGet();
        foreach ($res as $result) {
            $point = new \GeoJson\Geometry\Point([$result->getLongitudine(), $result->getLatitudine()]);
            $attributes = [
                "title" => "Porto di ".$result->getNome(),
                "description" => "Posti Totali: ".$result->getPostiTotale()."<br><br>Posti Transito: ".$result->getPostiTransito(),
                "marker-color" => "#fc4353",
                "marker-size" => "large",
                "marker-symbol" => "monument",
            ];
            $feature = new \GeoJson\Feature\Feature($point, $attributes, $result->getId());
            $json[] = $feature;

        }

        return new JsonResponse($json);
    }

    /**
     * @Route( "json_list", name="json_list_porto" )
     * @Template()
     */
    public function jsonlistAction(Request $request)
    {

        $json = array();
        $res = $this->cJsonGet();
        foreach ($res as $result) {
            $json[] = [
                "permalink"=>$result->getPermalink(),
                "name"=>$result->getNome()
            ];

        }

        return new JsonResponse($json);
    }

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

        $porti = $this->getDoctrine()
            ->getRepository('AppBundle:Porto')->findAll();

        return $this->render('AppBundle:Porto:dettagliPorto.html.twig', array("porti" => $porti));

    }


    /**
     * @Route("/{permalink}", name="dettaglio_porto")
     */
    public function dettagliPortoAction($permalink)
    {

     //   $client=$this->container->get('weather.client');



        $porto = $this->getDoctrine()
            ->getRepository('AppBundle:Porto')->findByPermalink($permalink);
        if(!$porto){


            throw $this->createNotFoundException('Unable to find Articolo.');
        }



      /*  $request = $client->get('/data/2.5/weather?lat='.$porto->getLatitudine().'&lon='.$porto->getLongitudine().'&APPID=8704a88837e9eabcf7b50de51728a0c0');
        $response = $client->send($request);
        $weather=json_decode($response->getBody(true));

        var_dump($weather);
*/
        return $this->render('AppBundle:Porto:dettagliPorto.html.twig', array("porti" => $porto));
    }



}
