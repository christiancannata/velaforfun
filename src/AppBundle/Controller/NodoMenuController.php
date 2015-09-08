<?php

namespace AppBundle\Controller;

use AppBundle\Form\MenuType;
use AppBundle\Form\NodoMenuType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class NodoMenuController extends BaseController
{

    protected $entity="NodoMenu";

    /**
     * @Route( "crea", name="create_nodomenu" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new NodoMenuType());
    }

    /**
     * @Route( "modifica/{id}", name="modifica_nodomenu" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository("AppBundle:".$this->entity)->find($id);

        $postform = $this->createForm(new NodoMenuType(), $entity);


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
            'AppBundle:Crud:create.html.twig',
            array('menu'=>$entity, 'form' => $postform->createView(), "titolo" => "Modifica ".$this->entity." - ".$id)
        );
    }


    /**
     * @Route( "list", name="list_nodomenu" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }



    /**
     * @Route( "elimina/{id}", name="delete_nodomenu" )
     * @Template()
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }



}
