<?php

namespace AppBundle\Controller;

use AppBundle\Form\AttraccoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Overlays\Animation;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Helper\MapHelper;


class AttraccoController extends BaseController
{

    protected $entity = "Attracco";

    /**
     * @Route( "crea", name="create_attracco" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new AttraccoType());
    }

    /**
     * @Route( "modifica/{id}", name="modifica_attracco" )
     * @Template()
     */
    public function patchAction(Request $request, $id)
    {
        return $this->patchForm($request, new AttraccoType(), $id, "Attracco");
    }


    /**
     * @Route( "list", name="list_attracco" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository("AppBundle:".$this->entity)->findAll();


        return $this->render('AppBundle:Crud:list-attracco.html.twig', array('entities' => $entities));
    }


    /**
     * @Route( "elimina/{id}", name="delete_attracco" )
     * @Template()
     */
    public function eliminaAction(Request $request, $id)
    {
        return $this->delete($id);
    }




}
