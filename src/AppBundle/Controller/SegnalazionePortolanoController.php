<?php

namespace AppBundle\Controller;

use AppBundle\Form\SegnalazionePortolanoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\Controller\Bar;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SegnalazionePortolanoController extends BaseController
{
    protected $entity = "SegnalazionePortolano";

    /**
     * @Route( "crea", name="create_segnalazione_portolano" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new SegnalazionePortolanoType());
    }


    /**
     * @Route( "modifica/{id}", name="modifica_segnalazione_portolano" )
     * @Template()
     */
    public function patchAction(Request $request, $id)
    {
        return $this->patchForm($request, new SegnalazionePortolanoType(), $id, "SegnalazionePortolano");
    }


    /**
     * @Route( "list", name="list_segnalazione_portolano" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }


    /*
     * {
    "type": "Feature",
    "geometry": {
      "type": "Point",
      "coordinates": [
        -77.03238901390978,
        38.913188059745586
      ]
    },
    "properties": {
      "title": "Mapbox DC",
      "description": "1714 14th St NW, Washington DC",
      "marker-color": "#fc4353",
      "marker-size": "large",
      "marker-symbol": "monument",
      "id": 1
    }
  }
     */
    /**
     * @Route( "geojson_list", name="geojson_list_segnalazione_portolano" )
     * @Template()
     */
    public function geoJsonlistAction(Request $request)
    {

        $json = array();
        $res = $this->cJsonGet();
        foreach ($res as $result) {
            $point = new \GeoJson\Geometry\Point([$result->getLongitudine(), $result->getLatitudine()]);
            $attributes = [
                "title" => "Segnalazione scritta da ".$result->getUtente()->getUsername(),
                "description" => $result->getDescrizione(),
                "marker-color" => "#fc4353",
                "marker-size" => "large",
                "marker-symbol" => "monument",
                "id" => 1
            ];
            $feature = new \GeoJson\Feature\Feature($point, $attributes, null);
            $json[] = $feature;

        }

        return new JsonResponse($json);
    }


    /**
     * @Route( "elimina/{id}", name="delete_segnalazione_portolano" )
     * @Template()
     */
    public function eliminaAction(Request $request, $id)
    {
        return $this->delete($id);
    }
}
