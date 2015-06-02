<?php

namespace BlogBundle\Controller;

use BlogBundle\Form\CategoriaType;
use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoriaController extends BaseController
{


    protected $entity="Categoria";



    /**
     * @Route( "crea", name="create_categoria" )
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request,new CategoriaType());
    }


    /**
     * @Route( "modifica/{id}", name="modifica_categoria" )
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new CategoriaType(),$id,"Categoria");
    }


    /**
     * @Route( "list", name="list_categoria" )
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }


    /**
     * @Route( "elimina/{id}", name="delete_categoria" )
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }



    /**
     * @Route("/{permalink}", name="categoria")
     */
    public function showAction($permalink)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $categoria = $em->getRepository('BlogBundle:Categoria')->findOneByPermalink($permalink);
        if (!$categoria) {
            throw $this->createNotFoundException('Unable to find Categoria.');
        }

        $articoli = $em->getRepository('BlogBundle:Articolo')->findBy(array('categoria' => $categoria, 'stato' => "ATTIVO"),array('id' => 'desc'));

        $categorie = $em->getRepository('BlogBundle:Categoria')->findAll();

        return $this->render(
            'BlogBundle:Categoria:categoria.html.twig',
            array(
                'articoli' => $articoli,
                'categoria' => $categoria,
                'categorie' => $categorie
            )
        );
    }
}
