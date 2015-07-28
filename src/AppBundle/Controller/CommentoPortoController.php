<?php

namespace AppBundle\Controller;

use AppBundle\Form\CommentoPortoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class CommentoPortoController extends BaseController
{

    protected $entity="CommentoPorto";

    /**
     * @Route( "crea", name="create_commento_porto" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new CommentoPortoType());
    }

    /**
     * @Route( "modifica/{id}", name="modifica_commento_porto" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new CommentoPortoType(),$id,"CommentoPorto");
    }


    /**
     * @Route( "list", name="list_commento_porto" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository("AppBundle:".$this->entity)->findAll();


        return $this->render('AppBundle:Crud:list-commenti-porto.html.twig', array('entities' => $entities));
    }



    /**
     * @Route( "elimina/{id}", name="delete_commento_porto" )
     * @Template()
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }


    /**
     * @Route("/{permalink}", name="dettaglio_commento_porto")
     */
    public function dettagliPortoAction($permalink)
    {

        if (is_numeric($permalink)) {
            $porto = $this->getDoctrine()
                ->getRepository('AppBundle:CommentoPorto')->find($permalink);


            $commento=array(
                "testo"=>$porto->getTesto(),
                "timestamp"=>$porto->getTimestamp()->format("d-m-Y H:i"),
                "tipo_commento"=>$porto->getTipoCommento(),
                "utente"=>array(
                    "facebook_i_d"=>$porto->getUtente()->getFacebookId(),
                    "profilePicturePath"=>$porto->getUtente()->getProfilePicturePath(),
                    "username"=>$porto->getUtente()->getUsername()
                )
            );

            return new JsonResponse($commento);
        }

    }
}
