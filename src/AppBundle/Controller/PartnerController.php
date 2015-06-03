<?php

namespace AppBundle\Controller;

use AppBundle\Form\PartnerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PartnerController extends BaseController
{
    protected $entity="Partner";
    /**
     * @Route( "crea", name="create_partner" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request,new PartnerType());
    }


    /**
     * @Route( "modifica/{id}", name="modifica_partner" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new PartnerType(),$id,"Partner");
    }


    /**
     * @Route( "list", name="list_partner" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }



    /**
     * @Route( "elimina/{id}", name="delete_partner" )
     * @Template()
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }


    /**
     * @Route("/", name="partner")
     */
    public function partnerAction()
    {

        $porti = $this->getDoctrine()
            ->getRepository('AppBundle:Partner')->findAll();
        $titolo = "Partner";

        return $this->render('AppBundle:Partner:partner.html.twig', array("partner" => $porti, "titolo" => $titolo));

    }


    /**
     * @Route("/{permalink}", name="partner_singolo")
     */
    public function dettagliPartnerAction($permalink)
    {

        $porti = $this->getDoctrine()
            ->getRepository('AppBundle:Partner')->findByPermalink($permalink);
        $titolo = "Partner";

        return $this->render('AppBundle:Partner:dettagliPartner.html.twig', array("partner" => $porti, "titolo" => $titolo));

    }
}
