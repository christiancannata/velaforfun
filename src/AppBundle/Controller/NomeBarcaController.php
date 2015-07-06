<?php

namespace AppBundle\Controller;

use AppBundle\Form\NomeBarcaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\Controller\Bar;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class NomeBarcaController extends BaseController
{
    protected $entity = "NomeBarca";

    /**
     * @Route( "crea", name="create_barca" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new NomeBarcaType());
    }


    /**
     * @Route( "modifica/{id}", name="modifica_barca" )
     * @Template()
     */
    public function patchAction(Request $request, $id)
    {
        return $this->patchForm($request, new NomeBarcaType(), $id, "Barca");
    }



    /**
     * @Route( "vota/{id}", name="vota_barca" )
     * @Template()
     */
    public function votaAction(Request $request, $id)
    {

        $barca=$this->getDoctrine()->getManager()->getRepository('AppBundle:NomeBarca')->find($id);

        if($barca){

            $barca->setPunti($barca->getPunti()+1);
            $this->getDoctrine()->getManager()->merge($barca);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse(array("response"=>"ok","success"=>true));
        }

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
    public function eliminaAction(Request $request, $id)
    {
        return $this->delete($id);
    }

    /**
     * @Route("/", name="nome_barca_home")
     */
    public function nomiBarcaAction()
    {

        $arrayBarche=array();
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:NomeBarca');
        $query = $repository->createQueryBuilder('p')
            ->orderBy("p.nome","DESC");

        $barche = $query->getQuery()->getResult();

        $arrayBarche = array();
        foreach ($barche as $barca){
            $firstone = substr( strtoupper(trim($barca->getNome()) ), 0 , 1 );
            $arrayBarche[$firstone][] = $barca;
        }

        $form['vars'] = array("full_name" => "appbundle_nome_barca");


        return $this->render('AppBundle:NomeBarca:lista.html.twig', array("barche" => array_reverse($arrayBarche),"form"=>$form));

    }


    /**
     * @Route("/{lettera}", name="nome_barca_singolo")
     */
    public function dettagliPartnerAction($lettera)
    {


        $titolo = "Partner";

        return $this->render('AppBundle:NomeBarca:lettera.html.twig', array("barche" => [], "titolo" => $titolo));

    }


}
