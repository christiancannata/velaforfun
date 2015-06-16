<?php

namespace AppBundle\Controller;

use AppBundle\Form\PaginaStaticaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class PaginaStaticaController extends BaseController
{

    protected $entity="PaginaStatica";

    /**
     * @Route( "crea", name="create_pagina_statica" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new PaginaStaticaType());
    }

    /**
     * @Route( "modifica/{id}", name="modifica_pagina_statica" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new PaginaStaticaType(),$id,"PaginaStatica");
    }


    /**
     * @Route( "list", name="list_pagina_statica" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }



    /**
     * @Route( "elimina/{id}", name="delete_pagina_statica" )
     * @Template()
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }



}
