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


class SegnaleController extends BaseController
{

    protected $entity = "Segnale";

    /**
     * @Route( "crea", name="create_segnale" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new SegnaleType());
    }

    /**
     * @Route( "modifica/{id}", name="modifica_segnale" )
     * @Template()
     */
    public function patchAction(Request $request, $id)
    {
        return $this->patchForm($request, new SegnaleType(), $id, "Segnale");
    }


    /**
     * @Route( "list", name="list_segnale" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }


    /**
     * @Route( "elimina/{id}", name="delete_segnale" )
     * @Template()
     */
    public function eliminaAction(Request $request, $id)
    {
        return $this->delete($id);
    }

    /**
     * @Route("/{frase}", name="segnali")
     */
    public function segnaliAction(Request $r, $frase = null)
    {

        $nodi = $this->getDoctrine()
            ->getRepository('AppBundle:Segnale')->findAll();

        $k = null;
        if ($frase) {
            $k = $frase;
        }

        return $this->render('AppBundle:Segnale:segnali.html.twig', array("segnali" => $nodi, "k" => $k));
    }


    /**
     * @Route("/jsondata", name="segnali_json")
     */
    public function nodiJsonAction()
    {
        $porti = $this->getDoctrine()
            ->getRepository('AppBundle:Segnale')->findAll();

        $arrayJson = [];
        foreach ($porti as $porto) {
            $arrayJson[] = array("permalink" => $porto->getPermalink(), "name" => $porto->getNome());
        }

        return new JsonResponse($arrayJson);
    }

    /**
     * @Route("/{permalink}", name="dettaglio_segnale")
     */
    public function dettagliNodoAction($permalink)
    {

        $porto = $this->getDoctrine()
            ->getRepository('AppBundle:Segnale')->findOneByPermalink($permalink);
        if (!$porto) {


            throw $this->createNotFoundException('Unable to find Articolo.');
        }


        return $this->render('AppBundle:Segnale:segnali.html.twig', array("nodi" => $porto));
    }


}
