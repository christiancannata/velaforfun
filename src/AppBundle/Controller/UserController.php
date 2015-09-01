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
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository("AppBundle:".$this->entity)->find($id);

        $postform = $this->createForm(new RegistrationCompletionFormType(), $entity);

        if ($request->isMethod('POST')) {

            $postform->handleRequest($request);

            if ($postform->isValid()) {


                /*
                 * $data['title']
                 * $data['body']
                 */
                $em = $this->getDoctrine()->getManager();

                $em->flush();


                $response['success'] = true;

            } else {

                $response['success'] = false;
                $response['cause'] = $postform->getErrors();

            }

            return new JsonResponse($response);
        }

        return $this->render(
            'AppBundle:Crud:create-user.html.twig',
            array(
                'form' => $postform->createView(),
                "titolo" => "Modifica ".$this->entity." - ".$id,
                "userInformation" => $entity
            )
        );
    }



    /**
     * @Route( "elimina-avatar/{id}", name="elimina_avatar_user" )
     */
    public function eliminaAvatarAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository("AppBundle:".$this->entity)->find($id);


        if ($request->isMethod('POST')) {


                $entity->setProfilePicturePath(null);

                $em = $this->getDoctrine()->getManager();

                $em->flush();


                $response['success'] = true;

            return new JsonResponse($response);
        }

    }


    /**
     * @Route( "list", name="list_user" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository("AppBundle:".$this->entity)->findAll();


        return $this->render('AppBundle:Crud:list-user.html.twig', array('entities' => $entities));
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
