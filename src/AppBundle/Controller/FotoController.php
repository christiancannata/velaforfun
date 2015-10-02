<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Foto;
use AppBundle\Entity\GalleriaFoto;
use AppBundle\Form\GalleriaFotoType;
use AppBundle\Form\FotoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class FotoController extends BaseController
{

    protected $entity = "Foto";

    /**
     * @Route( "crea", name="create_foto" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        $postform = $this->createForm(new FotoType());

        if ($request->isMethod('POST')) {

            $em = $this->getDoctrine()->getManager();
            $params = $request->request->all();


            $files = $request->files->get('appbundle_galleriafoto');

            $gallery = new GalleriaFoto();


            $gallery->setNome($params['appbundle_galleriafoto']['nome']);
            $gallery->setDescrizione($params['appbundle_galleriafoto']['descrizione']);
            $gallery->setInGallery(true);
            $em->persist($gallery);
            $em->flush();


            $foto = array();

            $files = $files['foto'];

            // $file will be an instance of Symfony\Component\HttpFoundation\File\UploadedFile
            foreach ($files as $uploadedFile) {
                $fileUpload = new Foto();
                $fileUpload->setProfilePictureFile($uploadedFile);
                $fileUpload->setNome(preg_replace('/\\.[^.\\s]{3,4}$/', '', $uploadedFile->getClientOriginalName()));
                $fileUpload->setGalleria($gallery);
                $fileUpload->setInEvidenza(true);
                $em->persist($fileUpload);
                $em->flush();
                $foto[] = $fileUpload;
            }


            $response['success'] = true;
            $response['response'] = $gallery->getId();


            return new JsonResponse($response);
        }

        return $this->render(
            'AppBundle:Foto:create.html.twig',
            array('form' => $postform->createView(), "titolo" => "Crea ".$this->entity)
        );
    }

    /**
     * @Route( "modifica/{id}", name="modifica_foto" )
     * @Template()
     */
    public function patchAction(Request $request, $id)
    {
        return $this->patchForm($request, new FotoType(), $id, "Foto");
    }


    /**
     * @Route( "list", name="list_foto" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }


    /**
     * @Route( "elimina/{id}", name="delete_foto" )
     * @Template()
     */
    public function eliminaAction(Request $request, $id)
    {
        return $this->delete($id);
    }

    /**
     * @Route("/", name="foto")
     */
    public function fotoAction(Request $request)
    {

        if ($request->isMethod('POST')) {

            return $this->postForm($request, new FotoType());
        }

        $categorie = $this->getDoctrine()
            ->getRepository('AppBundle:GalleriaFoto')->findBy(array('inGallery' => true), array('id' => 'desc'));

        foreach ($categorie as $key => $cat) {
            if (count($cat->getFoto()) == 0) {
                unset($categorie[$key]);
            }
        }

        $postform = $this->createForm(new FotoType());

        return $this->render(
            'AppBundle:Foto:gallerie.html.twig',
            array("gallerie" => $categorie, "form" => $postform->createView())
        );
    }


    /**
     * @Route("/jsondata", name="foto_json")
     */
    public function nodiJsonAction()
    {
        $porti = $this->getDoctrine()
            ->getRepository('AppBundle:Foto')->findAll();

        $arrayJson = [];
        foreach ($porti as $porto) {
            $arrayJson[] = array("permalink" => $porto->getPermalink(), "name" => $porto->getNome());
        }

        return new JsonResponse($arrayJson);
    }


    /**
     * @Route("/json", name="gallery_foto__json")
     */
    public function getGalleryJsonAction(Request $r)
    {


        $categorie = $this->getDoctrine()
            ->getRepository('AppBundle:GalleriaFoto')->findBy(
                array('inGallery' => true),
                array("id" => "desc"),
                3,
                $r->get('offset')
            );


        return $this->render(
            'AppBundle:Foto:ajax.html.twig',
            array("gallerie" => $categorie)

        );

    }


    /**
     * @Route("/{permalink}", name="dettaglio_foto")
     */
    public function dettagliFotoAction($permalink)
    {

        $categorie = $this->getDoctrine()
            ->getRepository('AppBundle:GalleriaFoto')->findOneByPermalink($permalink);
        if (!$categorie) {


            throw $this->createNotFoundException('Unable to find Articolo.');
        }


        return $this->render('AppBundle:Foto:dettagliGalleria.html.twig', array("galleria" => $categorie));
    }


    /**
     * @Route("/{permalink}/json", name="foto_gallery_json")
     */
    public function getFotoGalleryJsonAction($permalink, Request $r)
    {

        $categorie = $this->getDoctrine()
            ->getRepository('AppBundle:GalleriaFoto')->findOneByPermalink($permalink);
        if (!$categorie) {


            throw $this->createNotFoundException('Unable to find Categoria.');
        }

        $video = $this->getDoctrine()
            ->getRepository('AppBundle:Foto')->findBy(
                array("galleria" => $categorie),
                array("id" => "desc"),
                3,
                $r->get('offset')
            );


        return $this->render(
            'AppBundle:Foto:fotogallery-ajax.html.twig',
            array("fotos" => $video)

        );

    }


}
