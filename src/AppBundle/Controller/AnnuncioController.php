<?php

namespace AppBundle\Controller;

use AppBundle\Form\AnnuncioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\Controller\Bar;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AnnuncioController extends BaseController
{
    protected $entity="Annuncio";
    /**
     * @Route( "crea", name="create_annuncio" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request,new AnnuncioType());
    }


    /**
     * @Route( "modifica/{id}", name="modifica_annuncio" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new AnnuncioType(),$id,"Annuncio");
    }


    /**
     * @Route( "list", name="list_annuncio" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }



    /**
     * @Route( "elimina/{id}", name="delete_annuncio" )
     * @Template()
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }
}
