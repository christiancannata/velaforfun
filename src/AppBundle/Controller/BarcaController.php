<?php

namespace AppBundle\Controller;

use AppBundle\Form\PortoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BarcaController extends BaseController
{
    /**
     * @Route( "crea", name="create_post" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request,new PortoType() );
    }
}
