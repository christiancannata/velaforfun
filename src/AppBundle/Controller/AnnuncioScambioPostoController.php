<?php

namespace AppBundle\Controller;

use AppBundle\Form\AnnuncioScambioPostoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\Controller\Bar;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AnnuncioScambioPostoController extends BaseController
{
    protected $entity="AnnuncioScambioPosto";
    /**
     * @Route( "crea", name="create_annuncio_scambio_posto" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request,new AnnuncioScambioPostoType());
    }


    /**
     * @Route( "modifica/{id}", name="modifica_annuncio_scambio_posto" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new AnnuncioScambioPostoType(),$id,"AnnuncioScambioPosto");
    }


    /**
     * @Route( "list", name="list_annuncio_scambio_posto" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }



    /**
     * @Route( "elimina/{id}", name="delete_annuncio_scambio_posto" )
     * @Template()
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }
    /**
     * @Route("/", name="annunci_cambio_posto")
     */
    public function annunciScambioPostoAction()
    {

        $annunci = $this->getDoctrine()
            ->getRepository('AppBundle:AnnuncioScambioPosto')->findAll();
        $titolo="Annunci Imbarco";
        return $this->render('AppBundle:AnnuncioImbarco:lista.html.twig', array("annunci" => $annunci,"titolo"=>$titolo));

    }


    /**
     * @Route("/{permalink}", name="dettaglio_annuncio_imbarco")
     */
    public function dettagliAnnuncioImbarcoAction($permalink)
    {


        $annuncio = $this->getDoctrine()
            ->getRepository('AppBundle:AnnuncioScambioPosto')->findOneByPermalink($permalink);
        if(!$annuncio){


            throw $this->createNotFoundException('Unable to find Articolo.');
        }


        $titolo=$annuncio->getTitolo();

        return $this->render('AppBundle:AnnuncioScambioPosto:dettagliAnnuncio.html.twig', array("annuncio" => $annuncio,"titolo"=>$titolo));
    }
}
