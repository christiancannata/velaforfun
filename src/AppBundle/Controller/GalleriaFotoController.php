<?php

namespace AppBundle\Controller;

use AppBundle\Entity\GalleriaFoto;
use AppBundle\Entity\Foto;
use AppBundle\Form\GalleriaFotoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;


class GalleriaFotoController extends BaseController
{

    protected $entity="GalleriaFoto";

    /**
     * @Route( "crea", name="create_galleria_foto" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        $postform = $this->createForm(new GalleriaFotoType());

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
            ini_set('upload_max_filesize', '20M');
            foreach ($files as $uploadedFile) {

                $uploadedFile->move(
                    '/var/www/web/uploads/galleria_foto/',
                    $uploadedFile->getClientOriginalName()
                );

                $fileUpload = new Foto();
                $fileUpload->setImmagine( $uploadedFile->getClientOriginalName());
                $fileUpload->setNome($uploadedFile->getClientOriginalName());
                $fileUpload->setGalleria($gallery);
                $fileUpload->setInEvidenza(true);
                $em->persist($fileUpload);

                $foto[] = $fileUpload;
                // clean up the file property as you won't need it anymore
                $uploadedFile = null;

            }
            $em->flush();


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
     * @Route( "modifica/{id}", name="modifica_galleria_foto" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {


        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository("AppBundle:".$this->entity)->find($id);

        $postform = $this->createForm(new GalleriaFotoType());

        $fotos=$em->getRepository("AppBundle:Foto")->findByGalleria($entity);


        if ($request->isMethod('POST')) {

            $em = $this->getDoctrine()->getManager();
            $params = $request->request->all();


            $files = $request->files->get('appbundle_galleriafoto');




            $entity->setNome($params['appbundle_galleriafoto']['nome']);
            $entity->setDescrizione(trim($params['appbundle_galleriafoto']['descrizione']));
            $entity->setInGallery(true);
            $em->merge($entity);
            $em->flush();

            $foto = array();

            $files = $files['foto'];


            ini_set('upload_max_filesize', '20M');
            // $file will be an instance of Symfony\Component\HttpFoundation\File\UploadedFile
            foreach ($files as $uploadedFile) {


                $uploadedFile->move(
                    '/var/www/web/uploads/galleria_foto/',
                    $uploadedFile->getClientOriginalName()
                );

                    $fileUpload = new Foto();
                    $fileUpload->setImmagine( $uploadedFile->getClientOriginalName());
                    $fileUpload->setNome($uploadedFile->getClientOriginalName());
                    $fileUpload->setGalleria($entity);
                    $fileUpload->setInEvidenza(true);
                    $em->persist($fileUpload);

                    $foto[] = $fileUpload;
                // clean up the file property as you won't need it anymore
                $uploadedFile = null;

            }
            $em->flush();

            $response['success'] = true;
            $response['response'] = $entity->getId();


            return new JsonResponse($response);
        }



        return $this->render(
            'AppBundle:Foto:create.html.twig',
            array('form' => $postform->createView(), "titolo" => "Modifica ".$this->entity,"gallery"=>$entity,"fotos"=>$fotos)
        );
    }


    /**
     * @Route( "list", name="list_galleria_foto" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }



    /**
     * @Route( "elimina/{id}", name="delete_galleria_foto" )
     * @Template()
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }



    /**
     * @Route("/{permalink}", name="galleria_foto")
     */
    public function showAction($permalink)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $categoria = $em->getRepository('AppBundle:GalleriaFoto')->findOneByPermalink($permalink);
        if (!$categoria) {
            throw $this->createNotFoundException('Unable to find Categoria.');
        }

        $articoli = $em->getRepository('AppBundle:Articolo')->findBy(array('galleria' => $categoria, 'stato' => "ATTIVO"),array('id' => 'desc'));

        return $this->render(
            'AppBundle:GalleriaFoto:galleria.html.twig',
            array(
                'foto' => $articoli,
                'galleria' => $categoria
            )
        );
    }


}
