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
        return $this->cGet();
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
            ->addMeta('name', 'description', $articolo->getSottotitolo())
            ->addMeta('property', 'og:title', $articolo->getTitolo())
            ->addMeta('property', 'og:type', 'blog')
            ->addMeta('property', 'og:url', $routeName)
            ->addMeta(
                'property',
                'og:image',
                "http://local.velaforfun.dev/images/articoli/".$articolo->getProfilePictureFile()
            )
            ->addMeta('property', 'og:description', $articolo->getSottotitolo());

        $articoli = $em->getRepository('BlogBundle:Articolo')->findBy(
            array('categoria' => $articolo->getCategoria(), 'stato' => "ATTIVO"),
            array('lastUpdateTimestamp' => 'desc'),
            4
        );


        foreach($articoli as $key => $articoloRel){
            if($articoloRel->getId()==$articolo->getId()){
                unset($articoli[$key]);
            }
        }
        $categorie = $em->getRepository('BlogBundle:Categoria')->findAll();


        if(count($articoli)==4){
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
