<?php

namespace AppBundle\Controller;

use AppBundle\Form\BarcaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\Controller\Bar;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BarcaController extends BaseController
{
    protected $entity="Barca";
    /**
     * @Route( "crea", name="create_barca" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request,new BarcaType());
    }


    /**
     * @Route( "modifica/{id}", name="modifica_barca" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new BarcaType(),$id,"Barca");
    }


    /**
     * @Route( "list", name="list_barca" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }



    /**
     * @Route( "elimina/{id}", name="delete_barca" )
     * @Template()
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }
}
