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

    protected $entity = "Video";

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
    public function patchAction(Request $request, $id)
    {
        return $this->patchForm($request, new VideoType(), $id, "Video");
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
    public function eliminaAction(Request $request, $id)
    {
        return $this->delete($id);
    }


    /**
     * @Route("/json", name="gallery_json")
     */
    public function getGalleryJsonAction(Request $r)
    {


        $categorie = $this->getDoctrine()
            ->getRepository('AppBundle:CategoriaVideo')->findBy(
                array(),
                array("id"=>"desc"),
                3,
                $r->get('offset')
            );


        return $this->render(
            'AppBundle:Video:ajax.html.twig',
            array("gallerie" => $categorie)

        );

    }


    /**
     * @Route("/{permalink}", name="dettaglio_video")
     */
    public function dettagliVideoAction($permalink)
    {

        $categorie = $this->getDoctrine()
            ->getRepository('AppBundle:CategoriaVideo')->findOneByPermalink($permalink);
        if (!$categorie) {


            throw $this->createNotFoundException('Unable to find Categoria.');
        }


        return $this->render('AppBundle:Video:dettagliGalleria.html.twig', array("galleria" => $categorie));
    }


    /**
     * @Route("/{permalink}/json", name="video_gallery_json")
     */
    public function getVideoGalleryJsonAction($permalink, Request $r)
    {

        $categorie = $this->getDoctrine()
            ->getRepository('AppBundle:CategoriaVideo')->findOneByPermalink($permalink);
        if (!$categorie) {


            throw $this->createNotFoundException('Unable to find Categoria.');
        }

        $video = $this->getDoctrine()
            ->getRepository('AppBundle:Video')->findBy(
                array("categoria" => $categorie),
                array("id"=>"desc"),
                3,
                $r->get('offset')
            );


        return $this->render(
            'AppBundle:Video:videogallery-ajax.html.twig',
            array("videos" => $video)

        );

    }


    /**
     * @Route("/", name="video")
     */
    public function videoAction()
    {

        $categorie = $this->getDoctrine()
            ->getRepository('AppBundle:CategoriaVideo')->findBy(array(),array("id"=>"desc"), 3, 0);


        return $this->render('AppBundle:Video:gallerie.html.twig', array("gallerie" => $categorie));
    }


}
