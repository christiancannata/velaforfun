<?php

namespace AppBundle\Controller;

use AppBundle\Form\SegnalazionePortolanoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\Controller\Bar;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SegnalazionePortolanoController extends BaseController
{
    protected $entity="SegnalazionePortolano";
    /**
     * @Route( "crea", name="create_segnalazione_portolano" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request,new SegnalazionePortolanoType());
    }


    /**
     * @Route( "modifica/{id}", name="modifica_segnalazione_portolano" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new SegnalazionePortolanoType(),$id,"SegnalazionePortolano");
    }


    /**
     * @Route( "list", name="list_segnalazione_portolano" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }



    /**
     * @Route( "elimina/{id}", name="delete_segnalazione_portolano" )
     * @Template()
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }
}
