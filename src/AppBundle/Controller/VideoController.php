<?php

namespace AppBundle\Controller;

use AppBundle\Form\VideoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class VideoController extends BaseController
{

    protected $entity="Video";

    /**
     * @Route( "crea", name="create_video" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new VideoType());
    }

    /**
     * @Route( "modifica/{id}", name="modifica_video" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new VideoType(),$id,"Video");
    }


    /**
     * @Route( "list", name="list_video" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }



    /**
     * @Route( "elimina/{id}", name="delete_video" )
     * @Template()
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }

    /**
     * @Route("/{permalink}", name="dettaglio_video")
     */
    public function dettagliVideoAction($permalink)
    {

        $categorie = $this->getDoctrine()
            ->getRepository('AppBundle:CategoriaVideo')->findOneByPermalink($permalink);
        if(!$categorie){


            throw $this->createNotFoundException('Unable to find Categoria.');
        }


        return $this->render('AppBundle:Video:dettagliGalleria.html.twig', array("galleria" => $categorie));
    }

    /**
     * @Route("/", name="video")
     */
    public function videoAction()
    {

        $categorie = $this->getDoctrine()
            ->getRepository('AppBundle:CategoriaVideo')->findAll();


        return $this->render('AppBundle:Video:gallerie.html.twig', array("gallerie" => $categorie));
    }

}
