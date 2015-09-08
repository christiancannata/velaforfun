<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CategoriaVideo;
use AppBundle\Entity\GalleriaFoto;
use AppBundle\Entity\Foto;
use AppBundle\Form\CategoriaVideoType;
use AppBundle\Form\GalleriaFotoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;


class CategoriaVideoController extends BaseController
{

    protected $entity="CategoriaVideo";

    /**
     * @Route( "crea", name="create_categoria_video" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request,new CategoriaVideoType());
    }

    /**
     * @Route( "modifica/{id}", name="modifica_galleria_video" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new CategoriaVideoType(),$id,"CategoriaVideo");
    }


    /**
     * @Route( "list", name="list_galleria_video" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }



    /**
     * @Route( "elimina/{id}", name="delete_galleria_video" )
     * @Template()
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }



}
