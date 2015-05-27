<?php

namespace AppBundle\Controller;

use AppBundle\Form\AnnuncioScambioPostoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\Controller\Bar;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;

class AnnuncioScambioPostoController extends BaseController
{
    protected $entity = "AnnuncioScambioPosto";

    /**
     * @Route( "crea", name="create_annuncio_scambio_posto" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new AnnuncioScambioPostoType());
    }


    /**
     * @Route( "nuovo-annuncio", name="crea_nuovo_annuncio_scambio_posto" )
     * @Template()
     */
    public function nuovoAnnuncioAction(Request $request)
    {
        $postform = $this->createForm(new AnnuncioScambioPostoType());

        if ($request->isMethod('POST')) {

            $postform->handleRequest($request);

            if ($postform->isValid()) {

                $annuncio = $postform->getData();
                $em = $this->container->get('doctrine')->getManager();

                $repository = $this->container->get('doctrine')
                    ->getRepository('AppBundle:User');



                $firstTopic = new Topic();
                $firstTopic->setTitle("Scambio posto a ".$annuncio->getLuogoAttuale()->getNome()." con ".$annuncio->getLuogoRicercato());
                $firstTopic->setCachedViewCount(1);

                $firstTopic->setBoard(
                    $this->container->get('doctrine')
                        ->getRepository('CCDNForumForumBundle:Board')->find(11)
                );

                $em->persist($firstTopic);
                $em->flush();


                $post = new Post();
                $post->setTopic($firstTopic);
                $post->setCreatedDate(new \DateTime());
                $post->setCreatedBy(
                    $this->getUser()
                );


                $post->setBody($annuncio->getDescrizione());

                $em->persist($post);
                $em->flush();

                $annuncio->setTopic($firstTopic);
                $em->persist($annuncio);
                $em->flush();
                $response['success'] = true;
                $response['response'] = $annuncio->getId();


            } else {
                $response['success'] = false;


                $response['reponse'] = $this->getErrorsAsArray($postform);

            }

            return new JsonResponse($response);
        }

        return $this->render('AppBundle:AnnuncioImbarco:crea.html.twig', array("form" => $postform->createView()));
    }


    /**
     * @Route( "modifica/{id}", name="modifica_annuncio_scambio_posto" )
     * @Template()
     */
    public function patchAction(Request $request, $id)
    {
        return $this->patchForm($request, new AnnuncioScambioPostoType(), $id, "AnnuncioScambioPosto");
    }


    /**
     * @Route( "list", name="list_annuncio_scambio_posto" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }


    /**
     * @Route( "elimina/{id}", name="delete_annuncio_scambio_posto" )
     * @Template()
     */
    public function eliminaAction(Request $request, $id)
    {
        return $this->delete($id);
    }

    /**
     * @Route("/", name="annunci_cambio_posto")
     */
    public function annunciScambioPostoAction()
    {

        $annunci = $this->getDoctrine()
            ->getRepository('AppBundle:AnnuncioScambioPosto')->findAll();
        $titolo = "Annunci Imbarco";

        return $this->render(
            'AppBundle:AnnuncioImbarco:lista.html.twig',
            array("annunci" => $annunci, "titolo" => $titolo)
        );

    }


    /**
     * @Route("/{permalink}", name="dettaglio_annuncio_imbarco")
     */
    public function dettagliAnnuncioImbarcoAction($permalink)
    {


        $annuncio = $this->getDoctrine()
            ->getRepository('AppBundle:AnnuncioScambioPosto')->findOneByPermalink($permalink);
        if (!$annuncio) {


            throw $this->createNotFoundException('Unable to find Articolo.');
        }


        $titolo = $annuncio->getTitolo();

        return $this->render(
            'AppBundle:AnnuncioScambioPosto:dettagliAnnuncio.html.twig',
            array("annuncio" => $annuncio, "titolo" => $titolo)
        );
    }


}
