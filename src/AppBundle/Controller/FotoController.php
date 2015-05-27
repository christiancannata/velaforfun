<?php

namespace AppBundle\Controller;

use AppBundle\Form\FotoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class FotoController extends BaseController
{

    protected $entity="Foto";

    /**
     * @Route( "crea", name="create_foto" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new FotoType());
    }

    /**
     * @Route( "modifica/{id}", name="modifica_foto" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new FotoType(),$id,"Foto");
    }


    /**
     * @Route( "list", name="list_foto" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }



    /**
     * @Route( "elimina/{id}", name="delete_foto" )
     * @Template()
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }

    /**
     * @Route("/", name="foto")
     */
    public function nodiAction()
    {

        $nodi = $this->getDoctrine()
            ->getRepository('AppBundle:Foto')->findAll();


        return $this->render('AppBundle:Foto:foto.html.twig', array("foto" => $nodi));
    }


    /**
     * @Route("/jsondata", name="foto_json")
     */
    public function nodiJsonAction()
    {
        $porti = $this->getDoctrine()
            ->getRepository('AppBundle:Foto')->findAll();

        $arrayJson=[];
        foreach($porti as $porto){
            $arrayJson[]=array("permalink"=>$porto->getPermalink(),"name"=>$porto->getNome());
        }
        return new JsonResponse($arrayJson);
    }

    /**
     * @Route("/{permalink}", name="dettaglio_foto")
     */
    public function dettagliFotoAction($permalink)
    {

        $nodo = $this->getDoctrine()
            ->getRepository('AppBundle:Nodo')->findOneByPermalink($permalink);
        if(!$nodo){


            throw $this->createNotFoundException('Unable to find Articolo.');
        }


        return $this->render('AppBundle:Nodo:foto.html.twig', array("nodo" => $nodo,"nodi"=>$this->getDoctrine()
            ->getRepository('AppBundle:Nodo')->findAll()));
    }



}
