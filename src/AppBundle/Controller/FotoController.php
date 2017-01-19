<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Foto;
use AppBundle\Entity\GalleriaFoto;
use AppBundle\Form\GalleriaFotoType;
use AppBundle\Form\FotoType;
use Facebook\FacebookRequest;
use Facebook\FacebookSession;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use WallPosterBundle\Post\Post;


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


            $foto = array();

            $files = $files['foto'];

            $ore = $params['pubblicazione'];


            // $file will be an instance of Symfony\Component\HttpFoundation\File\UploadedFile
            foreach ($files as $key => $uploadedFile) {
                $fileUpload = new Foto();
                $fileUpload->setProfilePictureFile($uploadedFile);
                $fileUpload->setNome(preg_replace('/\\.[^.\\s]{3,4}$/', '', $uploadedFile->getClientOriginalName()));

                $tags = [];
                $tagGenerici = explode(",", $params['appbundle_galleriafoto']['tag']);
                $tagSpecifici = explode(",", $params['tags'][$key]);

                $tags = array_merge($tagGenerici, $tagSpecifici);

                $clear = [];
                foreach ($tags as $key => $tag) {
                    if (!empty(trim($tag))) {
                        $clear[] = $tag;
                    }
                }


                $fileUpload->setTag(strtoupper(json_encode($clear)));

                $galleria = $this->getDoctrine()
                    ->getRepository('AppBundle:GalleriaFoto')->find(1);

                $fileUpload->setGalleria($galleria);


                $fileUpload->setInEvidenza(true);


                $em->persist($fileUpload);


                $foto[] = $fileUpload;


                if ($ore > 0 && !$this->container->get('security.context')->getToken() instanceof UsernamePasswordToken) {


                    /** Create you Post instance **/
                    $fbPost = new Post();

                    /** Add image to post, you can provide absolute path for your local file and browser url to file **/

                    $immagineArticolo = "";
                    if ($fileUpload->getImmagine() != "") {
                        $immagineArticolo = 'galleria_foto/' . $fileUpload->getImmagine();
                    }


                    $fbPost = $fbPost->createImage(
                        $this->get('kernel')->getRootDir() . "/../web/uploads/" . $immagineArticolo,
                        'http://www.velaforfun.com/images/' . $immagineArticolo
                    )
                        /** Add social tags **/
                        /** Add message to your post **/
                        ->setMessage($fileUpload->getNome());

                    foreach ($tags as $tag) {
                        $fbPost->addTag($tag);
                    }


                    FacebookSession::setDefaultApplication('934348009960166', '84c4e12ab4042dd303245a991bf2fb20');

// Use one of the helper classes to get a FacebookSession object.
//   FacebookRedirectLoginHelper
//   FacebookCanvasLoginHelper
//   FacebookJavaScriptLoginHelper
// or create a FacebookSession with a valid access token:


                    if ($this->container->get('security.context')->getToken() instanceof UsernamePasswordToken) {
                        return new JsonResponse(
                            array(
                                "success" => false,
                                "response" => [
                                    "submit" => "Effettua il logout ed accedi tramite Facebook per poter condividere sui social!"
                                ]
                            )
                        );
                    }


                    $session = new FacebookSession($this->container->get('security.context')->getToken()->getAccessToken());
                    $idFacebook = "";
// Get the GraphUser object for the current user:
                    try {
                        $me = (
                        new FacebookRequest(
                            $session, 'GET', '/508027799222045?fields=access_token'
                        )
                        )->execute();


                        $pageToken = $me->getResponse();

                        if ($pageToken->access_token) {
                            $pageToken = $pageToken->access_token;
                        }


                        $session = new FacebookSession($pageToken);


                        $data = $request->request->all();


                        $em = $this->container->get('doctrine')->getManager();
                        $testo="";
                        foreach ($tags as $tag) {
                            if($tag!=""){
                                $testo.="#".$tag." ";

                            }
                        }

                        $post = array(
                            "message" => $testo,
                            "picture" => 'http://www.velaforfun.com/images/' . $immagineArticolo,
                            "link"=>'http://www.velaforfun.com/images/' . $immagineArticolo
                        );


                        if (isset($data['data_pubblicazione']) && $data['data_pubblicazione'] != "") {
                            $dataPubb = new \DateTime();
                            $dataPubb->add(new \DateInterval("PT{$ore}H"));
                            $dataPubb->setTimestamp($data['data_pubblicazione']);

                            $post['scheduled_publish_time'] = $data['data_pubblicazione'];
                            $post['published'] = false;
                            $ore += $ore;
                        }



                        //TODO: Handle errors
                        $facebookRequest = new FacebookRequest($session, 'POST', '/508027799222045/feed', $post);


                        /** @var GraphObject $graphObject */
                        try {

                            $graphObject = $facebookRequest->execute()->getGraphObject();


                            $idFacebook = $graphObject->getProperty('id');

                        } catch (\Exception $ex) {
                            die(var_dump($ex->getMessage()));
                        }


                    } catch (FacebookRequestException $e) {
                        var_dump($e->getMessage());
                        // The Graph API returned an error
                    } catch (\Exception $e) {
                        var_dump($e->getMessage());
                        // Some other error occurred
                    }


                   /* $fbPost = new Post();

                    $fbPost
                        ->createLink(
                            'http://www.velaforfun.com/archivio/' . $articolo->getCategoria()->getPermalink() . '/' . $articolo->getPermalink()
                        )
                        ->setMessage($articolo->getTitolo());

                    $provider = $this->get('wall_poster.twitter');

                    try {
                        $post = $provider->publish($fbPost);


                    } catch (Exception $ex) {

                        die(var_dump($ex->getMessage()));
                    } */


                    if ($idFacebook) {


                        return new JsonResponse(array("success" => true));
                    } else {
                        return new JsonResponse(
                            array(
                                "success" => false,
                                "error" => "(#200) The user hasn't authorized the application to perform this action"
                            )
                        );
                    }


                }
            }

            $em->flush();


            $response['success'] = true;
            $response['response'] = "ok";


            return new JsonResponse($response);
        }

        return $this->render(
            'AppBundle:Foto:create.html.twig',
            array('form' => $postform->createView(), "titolo" => "Crea " . $this->entity)
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
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository("AppBundle:" . $this->entity)->findAll();

        return $this->render('AppBundle:Foto:list.html.twig', array('entities' => $entities));
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
            ->getRepository('AppBundle:GalleriaFoto')->find(1);

        if (!$categorie) {


            throw $this->createNotFoundException('Unable to find Articolo.');
        }


        $tags = [];
        foreach ($categorie->getFoto() as $foto) {
            $tagsFoto = json_decode($foto->getTag(), true);

            if (is_array($tagsFoto)) {
                foreach ($tagsFoto as $tagFoto) {
                    if (!in_array($tagFoto, $tags)) {
                        $tags[] = $tagFoto;
                    }
                }
            }

        }


        return $this->render('AppBundle:Foto:dettagliGalleria.html.twig', array("galleria" => $categorie, "tags" => $tags));
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

        $query = $this->getDoctrine()->getManager()->createQuery("SELECT u FROM \\AppBundle\\Entity\\Foto u  where u.tag like '%\"" . strtoupper($permalink) . "\"%' order by u.id desc");
        $categorie = $query->setFirstResult(0)->setMaxResults(9)->getResult();


        if (!$categorie) {


            throw $this->createNotFoundException('Unable to find Articolo.');
        }


        return $this->render('AppBundle:Foto:dettagliGalleria.html.twig', array("tag" => $permalink, "galleria" => $categorie));
    }


    /**
     * @Route("/{permalink}/json", name="foto_gallery_json")
     */
    public function getFotoGalleryJsonAction($permalink, Request $r)
    {


        if($permalink=="all"){
            $query = $this->getDoctrine()->getManager()->createQuery("SELECT u FROM \\AppBundle\\Entity\\Foto u, \\AppBundle\\Entity\\GalleriaFoto g where u.galleria=g and g.inGallery=1 order by u.id desc");

        }else{
            $query = $this->getDoctrine()->getManager()->createQuery("SELECT u FROM \\AppBundle\\Entity\\Foto u  where u.tag like '%\"" . strtoupper($permalink) . "\"%'  order by u.id desc");

        }

        $video = $query->setFirstResult($r->get("offset"))->setMaxResults(9)->getResult();


        return $this->render(
            'AppBundle:Foto:fotogallery-ajax.html.twig',
            array("fotos" => $video)

        );

    }


}
