<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Meteo;
use AppBundle\Entity\Porto;
use AppBundle\Form\CommentoPortoType;
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

    protected $entity = "Porto";


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
                "description" => "Posti Totali: ".$result->getPostiTotale(
                    )."<br><br>Posti Transito: ".$result->getPostiTransito(),
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
                "permalink" => $result->getPermalink(),
                "name" => $result->getNome()
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
    public function patchAction(Request $request, $id)
    {
        return $this->patchForm($request, new PortoType(), $id, "Porto");
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
    public function eliminaAction(Request $request, $id)
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
        $titolo = "Porti d'Italia";

        return $this->render('AppBundle:Porto:porti.html.twig', array("porti" => $porti, "titolo" => $titolo));

    }

    /**
     * @Route("/jsondata", name="porti_italia_json")
     */
    public function portiItaliaJsonAction()
    {

        $porti = $this->getDoctrine()
            ->getRepository('AppBundle:Porto')->findAll();

        $arrayJson = [];
        foreach ($porti as $porto) {
            $arrayJson[] = array("permalink" => $porto->getPermalink(), "name" => $porto->getNome());
        }

        return new JsonResponse($arrayJson);
    }


    /**
     * @Route("/{permalink}", name="dettaglio_porto")
     */
    public function dettagliPortoAction($permalink)
    {

        $client = $this->container->get('weather.client');

        if (is_numeric($permalink)) {
            $porto = $this->getDoctrine()
                ->getRepository('AppBundle:Porto')->find($permalink);


            $serializer = $this->container->get('jms_serializer');
            $serializedPorto = $serializer->serialize($porto, "json");

            return new JsonResponse(json_decode($serializedPorto));
        }

        $porto = $this->getDoctrine()
            ->getRepository('AppBundle:Porto')->findOneByPermalink($permalink);
        if (!$porto) {


            throw $this->createNotFoundException('Unable to find Articolo.');
        }




        $dal = new \DateTime();
        $al = new \DateTime();
        $dal->sub(new \DateInterval("P120M"));




        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
        $qb->select('m')
            ->from('AppBundle:Meteo', 'm')
            ->where('m.porto = :porto')
            ->andWhere('m.timestamp between :dal and :al')
            ->setParameters(array('porto' => $porto, 'dal' => $dal, 'al' => $al));

        $meteo = $qb->getQuery()->getArrayResult();


        if (empty($meteo)) {


            $request = $client->get(
                '/data/2.5/weather?lat='.$porto->getLatitudine().'&lon='.$porto->getLongitudine(
                ).'&APPID=8704a88837e9eabcf7b50de51728a0c0&units=metric'
            );
            $response = $client->send($request);

            $entityMeteo = new Meteo();
            $entityMeteo->setData($response->getBody(true));
            $entityMeteo->setPorto($porto);


            $this->getDoctrine()->getManager()->persist($entityMeteo);
            $this->getDoctrine()->getManager()->flush();

            $weather = json_decode($response->getBody(true));
        } else {
            $meteo=$meteo[0]['data'];
            $weather = json_decode($meteo);
        }

        $titolo = "Porto di ".$porto->getNome();

        $postform = $this->createForm(new CommentoPortoType());


        return $this->render(
            'AppBundle:Porto:dettagliPorto.html.twig',
            array("porto" => $porto, "titolo" => $titolo, "meteo" => $weather, "form" => $postform->createView())
        );
    }

    /**
     * @Route("/{id}/jsondata", name="jsondata_porto")
     */
    public function meteoPortoAction($id)
    {

        $client = $this->container->get('weather.client');
        $serializer = $this->container->get('jms_serializer');

        $porto = $this->getDoctrine()
            ->getRepository('AppBundle:Porto')->find($id);
        if (!$porto) {


            return new JsonResponse([]);
        }


        $dal = new \DateTime();
        $al = new \DateTime();
        $dal->sub(new \DateInterval("P120M"));




        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();
        $qb->select('m')
            ->from('AppBundle:Meteo', 'm')
            ->where('m.porto = :porto')
            ->andWhere('m.timestamp between :dal and :al')
            ->setParameters(array('porto' => $porto, 'dal' => $dal, 'al' => $al));

        $meteo = $qb->getQuery()->getArrayResult();


        if (empty($meteo)) {


            $request = $client->get(
                '/data/2.5/weather?lat='.$porto->getLatitudine().'&lon='.$porto->getLongitudine(
                ).'&APPID=8704a88837e9eabcf7b50de51728a0c0&units=metric'
            );
            $response = $client->send($request);

            $entityMeteo = new Meteo();
            $entityMeteo->setData($response->getBody(true));
            $entityMeteo->setPorto($porto);


            $this->getDoctrine()->getManager()->persist($entityMeteo);
            $this->getDoctrine()->getManager()->flush();

            $meteo = json_decode($response->getBody(true));
        } else {
            $meteo=$meteo[0];
            $meteo = $meteo['data'];
            $meteo = json_decode($meteo);
        }

        $serializedPorto = $serializer->serialize($porto, "json");
        $data = array("porto" => json_decode($serializedPorto), "meteo" => $meteo);

        return new JsonResponse($data);
    }

}
