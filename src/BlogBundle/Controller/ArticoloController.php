<?php

namespace BlogBundle\Controller;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class ArticoloController extends BaseController
{

    protected $entity="Articolo";

    /**
     * @Route("/{categoria}/{permalink}", name="articolo")
     */
    public function showAction($categoria,$permalink)
    {
        $em = $this->getDoctrine()->getEntityManager();


        $articolo = $em->getRepository('BlogBundle:Articolo')->findOneByPermalink($permalink);
        if (!$articolo) {
            throw $this->createNotFoundException('Unable to find Articolo.');
        }

        return $this->render('BlogBundle:Articolo:articolo.html.twig', array(
            'articolo'      => $articolo,
        ));
    }




    /**
     * @Route( "crea", name="create_articolo" )
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request,new ArticoloType());
    }


    /**
     * @Route( "modifica/{id}", name="modifica_articolo" )
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new ArticoloType(),$id,"Articolo");
    }


    /**
     * @Route( "list", name="list_articolo" )
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }



    /**
     * @Route( "elimina/{id}", name="delete_articolo" )
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }
}
