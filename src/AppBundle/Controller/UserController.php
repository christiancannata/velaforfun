<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\RegistrationFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserController extends BaseController
{
    protected $entity="User";
    /**
     * @Route( "crea", name="create_user" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request,new RegistrationFormType());
    }


    /**
     * @Route( "modifica/{id}", name="modifica_user" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new RegistrationFormType(),$id,"User");
    }


    /**
     * @Route( "list", name="list_user" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }



    /**
     * @Route( "elimina/{id}", name="delete_user" )
     * @Template()
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }


    /**
     * @Route("/", name="user")
     */
    public function partnerAction()
    {

        $porti = $this->getDoctrine()
            ->getRepository('AppBundle:User')->findAll();
        $titolo = "Partner";

        return $this->render('AppBundle:User:user.html.twig', array("users" => $porti, "titolo" => $titolo));

    }


    /**
     * @Route("/{permalink}", name="user_singolo")
     */
    public function dettagliPartnerAction($permalink)
    {

        $porti = $this->getDoctrine()
            ->getRepository('AppBundle:User')->findByUsername($permalink);
        $titolo = "Partner";

        return $this->render('AppBundle:User:dettagliUtente.html.twig', array("user" => $porti, "titolo" => $titolo));

    }
}
