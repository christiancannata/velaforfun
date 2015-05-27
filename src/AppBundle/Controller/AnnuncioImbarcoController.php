<?php

namespace AppBundle\Controller;

use AppBundle\Form\AnnuncioImbarcoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\Controller\Bar;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AnnuncioImbarcoController extends BaseController
{
    protected $entity="AnnuncioImbarco";
    /**
     * @Route( "crea", name="create_annuncio_imbarco" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request,new AnnuncioImbarcoType());
    }


    /**
     * @Route( "modifica/{id}", name="modifica_annuncio_imbarco" )
     * @Template()
     */
    public function patchAction(Request $request,$id)
    {
        return $this->patchForm($request,new AnnuncioImbarcoType(),$id,"AnnuncioImbarco");
    }



    /**
     * @Route("/", name="annunci_imbarco")
     */
    public function annunciImbarcoAction()
    {

        $annunci = $this->getDoctrine()
            ->getRepository('AppBundle:AnnuncioImbarco')->findAll();
        $titolo="Annunci Imbarco";
        return $this->render('AppBundle:AnnuncioImbarco:lista.html.twig', array("annunci" => $annunci,"titolo"=>$titolo));

    }



    /**
     * @Route( "list", name="list_annuncio_imbarco" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }



    /**
     * @Route( "elimina/{id}", name="delete_annuncio_imbarco" )
     * @Template()
     */
    public function eliminaAction(Request $request,$id)
    {
        return $this->delete($id);
    }



    /**
     * @Route("/{permalink}", name="dettaglio_annuncio_imbarco")
     */
    public function dettagliAnnuncioImbarcoAction($permalink)
    {


        $annuncio = $this->getDoctrine()
            ->getRepository('AppBundle:AnnuncioImbarco')->findOneByPermalink($permalink);
        if(!$annuncio){


            throw $this->createNotFoundException('Unable to find Articolo.');
        }



        /*  $request = $client->get('/data/2.5/weather?lat='.$porto->getLatitudine().'&lon='.$porto->getLongitudine().'&APPID=8704a88837e9eabcf7b50de51728a0c0');
          $response = $client->send($request);
          $weather=json_decode($response->getBody(true));

          var_dump($weather);
  */
        $titolo=$annuncio->getTitolo();

        return $this->render('AppBundle:AnnuncioImbarco:dettagliAnnuncio.html.twig', array("annuncio" => $annuncio,"titolo"=>$titolo));
    }


    /**
     * @Route( "nuovo-annuncio", name="crea_nuovo_annuncio_imbarco" )
     * @Template()
     */
    public function nuovoAnnuncioAction(Request $request)
    {
        $postform = $this->createForm(new AnnuncioImbarcoType());
        if ($request->isMethod('POST')) {

            $postform->handleRequest($request);

            if ($postform->isValid()) {


                /*
                 * $data['title']
                 * $data['body']
                 */
                $data = $postform->getData();








                $em = $this->getDoctrine()->getManager();
                $em->persist($data);
                $em->flush();


                $response['success'] = true;
                $response['response'] = $data->getId();


            } else {
                $response['success'] = false;


                $response['reponse'] = $this->getErrorsAsArray($postform);

            }

            return new JsonResponse($response);
        }
        return $this->render('AppBundle:AnnuncioImbarco:crea.html.twig', array("form" => $postform->createView()));
    }
}
