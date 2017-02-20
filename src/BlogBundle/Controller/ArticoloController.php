<?php

namespace BlogBundle\Controller;

use AppBundle\Controller\BaseController;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Form\ArticoloType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Filesystem;


class ArticoloController extends BaseController
{

    protected $entity = "Articolo";


    /**
     * @Route( "articoli/crea", name="create_articolo" )
     */
    public function createAction(Request $request)
    {
        $postform = $this->createForm(new ArticoloType());

        if ($request->isMethod('POST')) {

            $postform->handleRequest($request);

            if ($postform->isValid()) {


                /*
                 * $data['title']
                 * $data['body']
                 */
                $data = $postform->getData();

                $params = $request->request->all();

                if($params['blogbundle_articolo']['textFileImage']!=""){
                    $data->setImmagineCorrelata(null);
                    $data->setImmagineCorrelataArticolo($params['blogbundle_articolo']['textFileImage']);
                }


                $em = $this->getDoctrine()->getManager();

                $em->persist($data);
                $em->flush();


                $response['success'] = true;
                $response['response'] = $data->getId();


            } else {
                $response['success'] = false;


                $response['response'] = $this->getErrorsAsArray($postform);

            }

            return new JsonResponse($response);
        }

        return $this->render(
            'AppBundle:Crud:create-articolo.html.twig',
            array('form' => $postform->createView(), "titolo" => "Crea")
        );
    }


    /**
     * @Route( "articoli/modifica/{id}", name="modifica_articolo" )
     */
    public function patchAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository("BlogBundle:".$this->entity)->find($id);

        $postform = $this->createForm(new ArticoloType(), $entity);

        if ($request->isMethod('POST')) {

            $postform->handleRequest($request);

            if ($postform->isValid()) {




                $data = $postform->getData();

                $params = $request->request->all();

                if($params['blogbundle_articolo']['textFileImage']!=""){
                    $data->setImmagineCorrelata(null);
                    $data->setImmagineCorrelataArticolo($params['blogbundle_articolo']['textFileImage']);
                }

                /*
                 * $data['title']
                 * $data['body']
                 */
                $em = $this->getDoctrine()->getManager();

                $em->merge($data);
                $em->flush();



                $response['success'] = true;

            } else {

                $response['success'] = false;
                $response['cause'] = $postform->getErrors();

            }

            return new JsonResponse($response);
        }


        return $this->render(
            'AppBundle:Crud:create-articolo.html.twig',
            array('form' => $postform->createView(), "titolo" => "Modifica ".$this->entity." - ".$id,"entity"=>$entity)
        );
    }


    /**
     * @Route( "list/articoli", name="list_articolo" )
     */
    public function listAction(Request $request)
    {

        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository("BlogBundle:".$this->entity)->findAll();

        $exclude = array(2,11,12,13,14,15);
        foreach ($entities as $key => $entity) {
            if ($entity->getCategoria() != null && in_array($entity->getCategoria()->getId(), $exclude)) {
                unset($entities[$key]);
            }
        }

        return $this->render('AppBundle:Crud:list-ricette.html.twig', array('entities' => $entities));
    }

    /**
     * @Route( "list/comunicati", name="list_comunicati" )
     */
    public function listComunicatiAction(Request $request)
    {

        $dql = "SELECT p FROM BlogBundle:Articolo p JOIN p.categoria c where c.id=2 order by p.id desc";
        $query = $this->getDoctrine()->getManager()->createQuery($dql)
            ->setFirstResult(0)
            ->setMaxResults(100);

        $paginator = new Paginator($query, $fetchJoinCollection = true);


        return $this->render('AppBundle:Crud:list-comunicati.html.twig', array('entities' => $paginator));
    }

    /**
     * @Route( "list/ricette", name="list_ricette" )
     */
    public function listRicetteAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entities = [];
        $categoria = $em->getRepository('BlogBundle:Categoria')->find(11);
        $entities = array_merge(
            $em->getRepository("BlogBundle:".$this->entity)->findByCategoria($categoria),
            $entities
        );


        $categoria = $em->getRepository('BlogBundle:Categoria')->find(12);
        $entities = array_merge(
            $em->getRepository("BlogBundle:".$this->entity)->findByCategoria($categoria),
            $entities
        );

        $categoria = $em->getRepository('BlogBundle:Categoria')->find(13);
        $entities = array_merge(
            $em->getRepository("BlogBundle:".$this->entity)->findByCategoria($categoria),
            $entities
        );

        $categoria = $em->getRepository('BlogBundle:Categoria')->find(14);
        $entities = array_merge(
            $em->getRepository("BlogBundle:".$this->entity)->findByCategoria($categoria),
            $entities
        );

        $categoria = $em->getRepository('BlogBundle:Categoria')->find(15);
        $entities = array_merge(
            $em->getRepository("BlogBundle:".$this->entity)->findByCategoria($categoria),
            $entities
        );

        return $this->render('AppBundle:Crud:list-ricette.html.twig', array('entities' => $entities));
    }


    /**
     * @Route( "articoli/elimina/{id}", name="delete_articolo" )
     */
    public function eliminaAction(Request $request, $id)
    {
        return $this->delete($id);
    }


    /**
     * @Route( "articoli/elimina-allegato/{id}", name="delete_allegato_articolo" )
     */
    public function eliminaAllegatoAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();


        $id=explode(",",$id);

        $entity = $em->getRepository("BlogBundle:".$this->entity)->find($id[0]);


        if ($request->isMethod('POST')) {

            $numeroAllegato = "setAllegato".$id[1];
            $entity->$numeroAllegato("");
            /*
             * $data['title']
             * $data['body']
             */
            $em = $this->getDoctrine()->getManager();
            $em->merge($entity);

            $em->flush();

            $response['success'] = true;

            return new JsonResponse($response);
        }

    }



    /**
     * @Route( "articoli/immagine-articolo/{id}", name="immagine_articolo" )
     */
    public function immagineArticoloAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();


        $id=explode(",",$id);

        $entity = $em->getRepository("BlogBundle:".$this->entity)->find($id[0]);


        if ($request->isMethod('POST')) {


            $foto = $em->getRepository("AppBundle:Foto")->find( $id[1]);


            $fs = new Filesystem();

            $fs->copy('/var/www/web/uploads/galleria_foto/'.$foto->getImmagine(), '/var/www/web/images/articoli/'.$foto->getImmagine());

            $entity->setImmagine($foto->getImmagine());
            /*
             * $data['title']
             * $data['body']
             */
            $em = $this->getDoctrine()->getManager();
            $em->merge($entity);

            $em->flush();

            $response['success'] = true;

            return new JsonResponse($response);
        }

    }



    /**
     * @Route( "articoli/elimina-immagine/{id}", name="delete_immagine_articolo" )
     */
    public function eliminaImmagineAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository("BlogBundle:".$this->entity)->find($id);


        if ($request->isMethod('POST')) {


            $entity->setImmagine(null);
            /*
             * $data['title']
             * $data['body']
             */
            $em = $this->getDoctrine()->getManager();
            $em->merge($entity);

            $em->flush();

            $response['success'] = true;

            return new JsonResponse($response);
        }

    }



    /**
     * @Route( "articoli/elimina-gallery/{id}", name="delete_gallery_articolo" )
     */
    public function eliminaGalleryAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository("BlogBundle:".$this->entity)->find($id);


        if ($request->isMethod('POST')) {


            $entity->setGallery(null);
            /*
             * $data['title']
             * $data['body']
             */
            $em = $this->getDoctrine()->getManager();
            $em->merge($entity);

            $em->flush();

            $response['success'] = true;

            return new JsonResponse($response);
        }

    }



    /**
     * @Route("/{categoria}/{permalink}", name="articolo")
     */
    public function showAction($categoria, $permalink)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->container->get('request');
        $routeName = $request->getUri();

        $articolo = $em->getRepository('BlogBundle:Articolo')->findOneByPermalink($permalink);
        if (!$articolo) {
            throw $this->createNotFoundException('Unable to find Articolo.');
        }


        $seoPage = $this->container->get('sonata.seo.page');

        $seoPage
            ->setTitle($articolo->getTitolo())
            ->addMeta('name', 'description', strip_tags($articolo->getSottotitolo()));

        $articoli = $em->getRepository('BlogBundle:Articolo')->findBy(
            array('categoria' => $articolo->getCategoria(), 'stato' => "ATTIVO"),
            array('lastUpdateTimestamp' => 'desc'),
            4
        );


        foreach ($articoli as $key => $articoloRel) {
            if ($articoloRel->getId() == $articolo->getId()) {
                unset($articoli[$key]);
            }
        }
        $categorie = $em->getRepository('BlogBundle:Categoria')->findAll();

        $exclude = array(11,12,13,14,15);
        foreach ($categorie as $key => $entity) {
            if ($entity != null && in_array($entity->getId(), $exclude)) {
                unset($categorie[$key]);
            }
        }
        if (count($articoli) == 4) {
            unset($articoli[3]);
        }

        return $this->render(
            'BlogBundle:Articolo:articolo.html.twig',
            array(
                'articolo' => $articolo,
                'articoli' => $articoli,
                "categorie" => $categorie
            )
        );
    }





}
