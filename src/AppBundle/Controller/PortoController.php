<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Attracco;
use AppBundle\Entity\Meteo;
use AppBundle\Entity\Porto;
use AppBundle\Form\CommentoPortoType;
use AppBundle\Form\PortoType;
use AppBundle\Twig\Extension\MenuVelaExtension;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


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
     * @Route( "select_list", name="select_list_porto" )
     * @Template()
     */
    public function selectListAction(Request $request)
    {

        $em = $this->getDoctrine()->getEntityManager();

        $repo = $em->getRepository("AppBundle:Porto");

        $regioni = $repo->createQueryBuilder('p')
            ->groupBy("p.regione")
            ->orderBy("p.regione")
            ->getQuery();

        $regioni = $regioni->getResult();
        $arrRegioni = array();

        foreach ($regioni as $regione) {

            $porti = $repo->findByRegione($regione->getRegione());
            $arrRegioni[] = array(
                "nome" => $regione->getRegione(),
                "porti" => $porti
            );
        }

        return $this->render('AppBundle:Porto:selectList.html.twig', array("regioni" =>$arrRegioni));
    }


    /**
     * @Route( "crea", name="create_porto" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        $postform = $this->createForm(new PortoType());

        if ($request->isMethod('POST')) {

            $postform->handleRequest($request);

            if ($postform->isValid()) {


                /*
                 * $data['title']
                 * $data['body']
                 */
                $data = $postform->getData();
                $em = $this->getDoctrine()->getManager();

                $em->persist($data);
                $em->flush();


                $response['success'] = true;
                $response['response'] = $data->getId();


            } else {
                $response['success'] = false;


                $response['response'] = $this->getErrorsAsArray($postform);

            }

            return new JsonResponse($response);
        }

        return $this->render(
            'AppBundle:Crud:create-porto.html.twig',
            array('form' => $postform->createView(), "titolo" => "Crea ".$this->entity)
        );
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


        $attracchi = $this->getDoctrine()
            ->getRepository('AppBundle:Attracco')->findBy(array(), array('timestamp' => 'DESC'), 5);

        $commenti = $this->getDoctrine()
            ->getRepository('AppBundle:CommentoPorto')->findBy(array(), array('timestamp' => 'DESC'), 5);


        return $this->render(
            'AppBundle:Porto:porti.html.twig',
            array("porti" => $porti, "titolo" => $titolo, "attracchi" => $attracchi, "commenti" => $commenti)
        );

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
            ->andWhere("m.data like '%\"cod\":\"200\"%'")
            ->setParameters(array('porto' => $porto, 'dal' => $dal, 'al' => $al));

        $meteo = $qb->getQuery()->getArrayResult();
        $dal = new \DateTime();
        $al = new \DateTime();
        $al->add(new \DateInterval("P2D"));

        if (empty($meteo)) {


            $risposta=array();

            while($risposta['cod']=="200") {
                $request = $client->get(
                    '/data/2.5/forecast?lat='.$porto->getLatitudine().'&lon='.$porto->getLongitudine(
                    ).'&APPID=8704a88837e9eabcf7b50de51728a0c0&type=day&units=metric'
                );
                $response = $client->send($request);


                $risposta=json_decode($response->getBody(true),true);

            }

            $entityMeteo = new Meteo();
            $entityMeteo->setData(json_encode($risposta));
            $entityMeteo->setPorto($porto);


            $this->getDoctrine()->getManager()->persist($entityMeteo);
            $this->getDoctrine()->getManager()->flush();

            $weather = json_decode($response->getBody(true));

        } else {
            $meteo = $meteo[0]['data'];
            $weather = json_decode($meteo);
        }

        $titolo = "Porto di ".$porto->getNome();

        $postform = $this->createForm(new CommentoPortoType());


        $mainMeteo=new \StdClass;

        $trovato=false;
        $now=new \DateTime();
        $meteoAfter=[];

        foreach($weather->list as $key=>$meteo){
            $orario=new \DateTime();
            $orario->setTimestamp($meteo->dt);
            if($orario->getTimestamp() >= $now->getTimestamp()   && !$trovato){
                $trovato=true;
                $mainMeteo=$meteo;
            }
            if($trovato && $mainMeteo->dt!=$meteo->dt){
                $meteoAfter[]=$meteo;
            }

        }



        $now=new \DateTime();
        $now->setTimestamp($mainMeteo->dt);

        return $this->render(
            'AppBundle:Porto:dettagliPorto.html.twig',
            array("porto" => $porto, "titolo" => $titolo,"mainMeteo"=>$mainMeteo, "meteo" => $meteoAfter, "form" => $postform->createView())
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
                '/data/2.5/forecast?lat='.$porto->getLatitudine().'&lon='.$porto->getLongitudine(
                ).'&APPID=8704a88837e9eabcf7b50de51728a0c0&type=day&units=metric'
            );
            $response = $client->send($request);

            $entityMeteo = new Meteo();
            $entityMeteo->setData($response->getBody(true));
            $entityMeteo->setPorto($porto);


            $this->getDoctrine()->getManager()->persist($entityMeteo);
            $this->getDoctrine()->getManager()->flush();

            $meteo = json_decode($response->getBody(true));
        } else {
            $meteo = $meteo[0];
            $meteo = $meteo['data'];
            $meteo = json_decode($meteo);
        }

        $serializedPorto = $serializer->serialize($porto, "json");
        $data = array("porto" => json_decode($serializedPorto), "meteo" => $meteo);

        return new JsonResponse($data);
    }


    /**
     * @Route("/localizzami/jsondata", name="jsondata_porto")
     */
    public function localizzamiAction(Request $request)
    {
        $utils = new MenuVelaExtension();
        $client = $this->container->get('weather.client');

        $request = $client->get(
            '/data/2.5/forecast?lat='.$request->get("lat").'&lon='.$request->get(
                "long"
            ).'&APPID=8704a88837e9eabcf7b50de51728a0c0&type=day&units=metric'
        );

        $response = $client->send($request);

        $weather = json_decode($response->getBody(true));

        $mainMeteo=new \StdClass;

        $trovato=false;
        $now=new \DateTime();
        $meteoAfter=[];

        foreach($weather->list as $key=>$meteo){
            $orario=new \DateTime();
            $orario->setTimestamp($meteo->dt);
            if($orario->getTimestamp() >= $now->getTimestamp()   && !$trovato){
                $trovato=true;
                $time=new \DateTime();
                $time->setTimestamp($meteo->dt);
                $meteo->time = $time->format("H:i");
                $meteo->name=$weather->city->name;
                $mainMeteo=$meteo;
            }
            if($trovato && $mainMeteo->dt!=$meteo->dt){

                $time=new \DateTime();
                $time->setTimestamp($meteo->dt);
                $meteo->time = $time->format("H:i");

                $icon = $utils->getMeteo($meteo->weather[0]->icon);
                $meteo->weather[0]->icon = $icon;
                $meteoAfter[]=$meteo;
            }

        }


        $now=new \DateTime();
        $now->setTimestamp($mainMeteo->dt);



        $icon = $utils->getMeteo($mainMeteo->weather[0]->icon);
        $mainMeteo->weather[0]->icon = $icon;
        $data = array("geoposition" => $mainMeteo,"altroMeteo"=>$meteoAfter);

        return new JsonResponse($data);
    }


    /**
     * @Route("/{id}/attracca", name="attracca_porto")
     */
    public function attraccaPortoAction($id)
    {

        $porto = $this->getDoctrine()
            ->getRepository('AppBundle:Porto')->find($id);
        if (!$porto) {


            return new JsonResponse(array("response" => false));
        }


        $attracca = new Attracco();
        $attracca->setPorto($porto);
        $attracca->setUtente($this->getUser());

        $this->getDoctrine()->getManager()->persist($attracca);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(array("response" => true));
    }


}
