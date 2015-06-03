<?php

namespace AppBundle\Controller;

use AppBundle\Form\NodoMenuType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class NodoMenuController extends BaseController
{

    protected $entity="NodoMenu";

    /**
     * @Route( "crea", name="create_nodomenu" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new NodoMenuType());
    }

    /**
     * @Route( "modifica/{id}", name="modifica_nodomenu" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new NodoMenuType(),$id,$this->entity);
    }


    /**
     * @Route( "list", name="list_nodomenu" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }



    /**
     * @Route( "elimina/{id}", name="delete_nodomenu" )
     * @Template()
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }



}
