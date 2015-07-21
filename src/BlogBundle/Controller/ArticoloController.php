<?php

namespace BlogBundle\Controller;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Form\ArticoloType;

class ArticoloController extends BaseController
{

    protected $entity = "Articolo";


    /**
     * @Route( "articoli/crea", name="create_articolo" )
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new ArticoloType());
    }


    /**
     * @Route( "articoli/modifica/{id}", name="modifica_articolo" )
     */
    public function patchAction(Request $request, $id)
    {
        return $this->patchForm($request, new ArticoloType(), $id, "Articolo");
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
        $em = $this->getDoctrine()->getEntityManager();

        $categoria = $em->getRepository('BlogBundle:Categoria')->find(2);
        $entities = $em->getRepository("BlogBundle:".$this->entity)->findByCategoria($categoria);


        return $this->render('AppBundle:Crud:list-comunicati.html.twig', array('entities' => $entities));
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
            ->addMeta('name', 'description', strip_tags($articolo->getSottotitolo()))
            ->addMeta('property', 'og:title', $articolo->getTitolo())
            ->addMeta('property', 'og:type', 'blog')
            ->addMeta('property', 'og:url', $routeName)
            ->addMeta(
                'property',
                'og:image',
                "http://www.velaforfun.com/images/articoli/".$articolo->getProfilePictureFile()
            )
            ->addMeta('property', 'og:description', strip_tags($articolo->getSottotitolo()));

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
