<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\RegistrationFormType;
use AppBundle\Form\Type\RegistrationCompletionFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserController extends BaseController
{
    protected $entity = "User";

    /**
     * @Route( "crea", name="create_user" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new RegistrationFormType());
    }


    /**
     * @Route( "modifica/{id}", name="modifica_user" )
     * @Template()
     */
    public function patchAction(Request $request, $id)
    {
        return $this->patchForm($request, new RegistrationCompletionFormType(), $id, "User");
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
    public function eliminaAction(Request $request, $id)
    {
        return $this->delete($id);
    }


    /**
     * @Route("/{username}", name="dettagli_profilo")
     */
    public function dettagliProfiloAction($username)
    {

        $user = $repository = $this->getDoctrine()
            ->getRepository('AppBundle:User')->findOneByUsername($username);
        if (!$user) {
            return $this->redirect('/');
        }
        $annunciImbarco = $repository = $this->getDoctrine()
            ->getRepository('AppBundle:AnnuncioImbarco')->findByUtente($user);

        $annunciScambio = $repository = $this->getDoctrine()
            ->getRepository('AppBundle:AnnuncioScambioPosto')->findByUtente($user);


        $repository = $this->getDoctrine()
            ->getRepository('BlogBundle:Articolo');

        $articoli = $repository->findByStato("ATTIVO", array('id' => 'desc'), 4);


        return $this->render(
            'default/profilo-utente.html.twig',
            array(
                "annunciImbarco" => $annunciImbarco,
                "annunciScambio" => $annunciScambio,
                "user" => $user,
                "articoli" => $articoli
            )
        );
    }

}
