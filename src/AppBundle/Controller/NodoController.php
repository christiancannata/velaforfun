<?php

namespace AppBundle\Controller;

use AppBundle\Form\NodoType;
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

    protected $entity="Nodo";

    /**
     * @Route( "crea", name="create_nodo" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new NodoType());
    }

    /**
     * @Route( "modifica/{id}", name="modifica_nodo" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new NodoType(),$id,"Nodo");
    }


    /**
     * @Route( "list", name="list_nodo" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }



    /**
     * @Route( "elimina/{id}", name="delete_nodo" )
     * @Template()
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }

    /**
     * @Route("/", name="nodi")
     */
    public function nodiAction()
    {

        $nodi = $this->getDoctrine()
            ->getRepository('AppBundle:Nodo')->findAll();


        return $this->render('AppBundle:Nodo:nodi.html.twig', array("nodi" => $nodi));
    }


    /**
     * @Route("/jsondata", name="nodi_json")
     */
    public function nodiJsonAction()
    {
        $porti = $this->getDoctrine()
            ->getRepository('AppBundle:Nodo')->findAll();

        $arrayJson=[];
        foreach($porti as $porto){
            $arrayJson[]=array("permalink"=>$porto->getPermalink(),"name"=>$porto->getNome());
        }
        return new JsonResponse($arrayJson);
    }

    /**
     * @Route("/{permalink}", name="dettaglio_nodo")
     */
    public function dettagliNodoAction($permalink)
    {

        $porto = $this->getDoctrine()
            ->getRepository('AppBundle:Nodo')->findOneByPermalink($permalink);
        if(!$porto){


            throw $this->createNotFoundException('Unable to find Articolo.');
        }


        return $this->render('AppBundle:Nodo:nodi.html.twig', array("nodi" => $nodi));
    }



}
