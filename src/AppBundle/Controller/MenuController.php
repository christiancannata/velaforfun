<?php

namespace AppBundle\Controller;

use AppBundle\Form\MenuType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Overlays\Animation;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Helper\MapHelper;


class MenuController extends BaseController
{

    protected $entity="Menu";

    /**
     * @Route( "crea", name="create_menu" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new MenuType());
    }

    /**
     * @Route( "modifica/{id}", name="modifica_menu" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new MenuType(),$id,$this->entity);
    }


    /**
     * @Route( "list", name="list_menu" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }



    /**
     * @Route( "elimina/{id}", name="delete_menu" )
     * @Template()
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }



}
