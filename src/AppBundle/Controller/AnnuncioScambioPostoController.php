<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Porto;
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
     * @Route( "nuovo-annuncio", name="crea_nuovo_annuncio_scambio_posto" )
     * @Template()
     */
    public function nuovoAnnuncioAction(Request $request)
    {
        $postform = $this->createForm(new AnnuncioScambioPostoType());

        if ($request->isMethod('POST')) {

            $postform->handleRequest($request);

            if ($postform->isValid()) {

                $em = $this->container->get('doctrine')->getManager();

                $annunciUtente = $this->getDoctrine()
                    ->getRepository('AppBundle:AnnuncioScambioPosto')->findBy(array("utente" => $this->getUser()));

                foreach ($annunciUtente as $annuncioUtente) {

                    $em->remove($annuncioUtente->getTopic());
                    $em->remove($annuncioUtente);
                }


                $annuncio = $postform->getData();


                $repository = $this->container->get('doctrine')
                    ->getRepository('AppBundle:User');


                $board = $this->container->get('doctrine')
                    ->getRepository('CCDNForumForumBundle:Board')->find(11);

                $firstTopic = new Topic();
                $luogoCercato = ucwords(str_replace("_", " ", strtolower($annuncio->getLuogoRicercato())));


                $firstTopic->setTitle(
                    "Scambio posto a ".$annuncio->getLuogoAttuale()->getNome()." con ".$luogoCercato
                );
                $firstTopic->setCachedViewCount(1);

                $firstTopic->setBoard(
                    $board
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


                $this->allertaAnnunci($annuncio);

                $firstTopic->setFirstPost($post);
                $firstTopic->setLastPost($post);
                $em->merge($firstTopic);
                $board->setLastPost($post);
                $em->persist($board);
                $em->flush();

                $response['success'] = true;
                $response['response'] = $firstTopic->getId();


            } else {
                $response['success'] = false;


                $response['response'] = $this->getErrorsAsArray($postform);

            }

            return new JsonResponse($response);
        }

        return $this->render('AppBundle:AnnuncioScambioPosto:crea.html.twig', array("form" => $postform->createView()));
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
            ->getRepository('AppBundle:AnnuncioScambioPosto')->findBy(array(), array('id' => 'desc'), 8);
        $titolo = "Annunci Scambio Posto Barca";


        $form['vars'] = array("full_name" => "appbundle_annuncioscambioposto");


        return $this->render(
            'AppBundle:AnnuncioScambioPosto:lista.html.twig',
            array("annunci" => $annunci, "titolo" => $titolo, "form" => $form)
        );

    }


    /**
     * @Route( "cerca-annuncio", name="cerca_annuncio_scambio_posto" )
     * @Template()
     */
    public function cercaAnnuncioAction(Request $request)
    {


        $annunci = $this->getDoctrine()
            ->getRepository('AppBundle:AnnuncioScambioPosto')->findAll();

        $postform = $this->createForm(new AnnuncioScambioPostoType());

        if ($request->isMethod('POST')) {
            $params = $request->request->all();
            if (isset($params['appbundle_annuncioscambioposto']['notifica']) && $params['appbundle_annuncioscambioposto']['notifica'] == 1) {
                unset($params['appbundle_annuncioscambioposto']['notifica']);
                $request->request->set('appbundle_annuncioscambioposto', $params['appbundle_annuncioscambioposto']);
                $postform->handleRequest($request);
                if ($postform->isValid()) {
                    $annuncio = $postform->getData();
                    $em = $this->container->get('doctrine')->getManager();

                    $repository = $this->container->get('doctrine')
                        ->getRepository('AppBundle:User');


                    $user = $this->getUser();

                    $em = $this->container->get('doctrine')->getManager();

                    $annunciUtente = $this->getDoctrine()
                        ->getRepository('AppBundle:AnnuncioScambioPosto')->findBy(array("utente" => $user));

                    foreach ($annunciUtente as $annuncioUtente) {

                        $em->remove($annuncioUtente->getTopic());
                        $em->remove($annuncioUtente);
                    }

                    $luogoCercato = ucwords(str_replace("_", " ", strtolower($annuncio->getLuogoRicercato())));

                    $annuncio->setUtente($user);
                    $annuncio->setDescrizione(
                        "Scambio posto a ".$annuncio->getLuogoAttuale()->getNome()." con ".$luogoCercato
                    );
                    $firstTopic = new Topic();
                    $firstTopic->setTitle(
                        "Scambio posto a ".$annuncio->getLuogoAttuale()->getNome()." con ".$luogoCercato
                    );
                    $firstTopic->setCachedViewCount(1);
                    $board = null;


                    $board = $this->container->get('doctrine')
                        ->getRepository('CCDNForumForumBundle:Board')->find(11);


                    $firstTopic->setBoard(
                        $board
                    );

                    $em->persist($firstTopic);
                    $em->flush();


                    $post = new Post();
                    $post->setTopic($firstTopic);
                    $post->setCreatedDate(new \DateTime());
                    $post->setCreatedBy(
                        $user
                    );


                    $post->setBody($annuncio->getDescrizione());

                    $em->persist($post);
                    $em->flush();


                    $annuncio->setTopic($firstTopic);
                    $em->persist($annuncio);
                    $em->flush();


                    $this->allertaAnnunci($annuncio);

                    $firstTopic->setFirstPost($post);
                    $firstTopic->setLastPost($post);
                    $em->merge($firstTopic);
                    $board->setLastPost($post);
                    $em->persist($board);
                    $em->flush();


                } else {
                    die(var_dump($this->getErrorsAsArray($postform)));

                }

            }




            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:AnnuncioScambioPosto');
            $query = $repository->createQueryBuilder('p');
            $annunci=array();
            if ($params['appbundle_annuncioscambioposto']['luogoRicercato'] != "TUTTO") {

                $porto = $this->getDoctrine()
                    ->getRepository('AppBundle:Porto')->find($params['appbundle_annuncioscambioposto']['luogoRicercato']);

                $annunci = $repository->findBy(array("luogoAttuale"=>$porto),array("id"=>"desc"));
            }else{
                $annunci = $repository->findBy(array(),array("id"=>"desc"));
            }





            $serializer = $this->container->get('jms_serializer');
            $serialized = $serializer->serialize($annunci, "json");

            return new JsonResponse(json_decode($serialized));
        }

        return $this->render(
            'AppBundle:AnnuncioScambioPosto:cerca.html.twig',
            array("annunci" => $annunci, "form" => $postform->createView())
        );
    }

    private function allertaAnnunci($annuncio)
    {

        // CHI CREA INVIA A TUTTI QUELLI CHE ERANO IN ATTESA





    }

}
