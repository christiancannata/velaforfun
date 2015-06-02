<?php

namespace AppBundle\Controller;

use AppBundle\Form\GalleriaFotoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class GalleriaFotoController extends BaseController
{

    protected $entity="GalleriaFoto";

    /**
     * @Route( "crea", name="create_galleria_foto" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new GalleriaFotoType());
    }

    /**
     * @Route( "modifica/{id}", name="modifica_galleria_foto" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new GalleriaFotoType(),$id,$this->entity);
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
