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

            // $file will be an instance of Symfony\Component\HttpFoundation\File\UploadedFile
            foreach ($files as $uploadedFile) {
                $fileUpload = new Foto();
                $fileUpload->setProfilePictureFile($uploadedFile);
                $fileUpload->setNome(preg_replace('/\\.[^.\\s]{3,4}$/', '',$uploadedFile->getClientOriginalName()));
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



            $fs = new Filesystem();
            // $file will be an instance of Symfony\Component\HttpFoundation\File\UploadedFile
            foreach ($files as $uploadedFile) {

                try {
                    $path_parts = pathinfo($uploadedFile->filePath);

                    $fs->copy($uploadedFile->filePath, '/var/www/web/uploads/galleria_foto/'.$path_parts['filename'].".".$path_parts['extension']);



                    $fileUpload = new Foto();
                    $fileUpload->setImmagine($path_parts['filename'].".".$path_parts['extension']);
                    $fileUpload->setNome($path_parts['filename']);
                    $fileUpload->setGalleria($entity);
                    $fileUpload->setInEvidenza(true);
                    $em->persist($fileUpload);

                    $foto[] = $fileUpload;
                } catch (IOException $e) {
                    echo "Errore durante la copia del file";
                }

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
