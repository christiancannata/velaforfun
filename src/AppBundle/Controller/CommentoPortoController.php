<?php

namespace AppBundle\Controller;

use AppBundle\Form\CommentoPortoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class CommentoPortoController extends BaseController
{

    protected $entity="CommentoPorto";

    /**
     * @Route( "crea", name="create_commento_porto" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new CommentoPortoType());
    }

    /**
     * @Route( "modifica/{id}", name="modifica_commento_porto" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new CommentoPortoType(),$id,"CommentoPorto");
    }


    /**
     * @Route( "list", name="list_commento_porto" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }



    /**
     * @Route( "elimina/{id}", name="delete_commento_porto" )
     * @Template()
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }



}
