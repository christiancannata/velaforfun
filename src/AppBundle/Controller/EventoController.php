<?php

namespace AppBundle\Controller;

use AppBundle\Form\EventoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\Controller\Bar;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class EventoController extends BaseController
{
    protected $entity="Evento";
    /**
     * @Route( "crea", name="create_evento" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request,new EventoType());
    }


    /**
     * @Route( "modifica/{id}", name="modifica_evento" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new EventoType(),$id,"Evento");
    }


    /**
     * @Route( "list", name="list_evento" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }



    /**
     * @Route( "elimina/{id}", name="delete_evento" )
     * @Template()
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }
}
