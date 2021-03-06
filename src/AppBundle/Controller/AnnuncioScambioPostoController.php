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

                $luogoCercato = ucwords(str_replace("_", " ", strtolower($annuncio->getLuogoRicercato())));


                $oldAnnuncio = $this->container->get('doctrine')
                    ->getRepository('AppBundle:AnnuncioImbarco')->findOneBy(array("titolo"=>"Scambio posto a ".$annuncio->getLuogoAttuale()->getNome()." con ".$luogoCercato,"utente"=>$this->getUser()));

                if($oldAnnuncio){
                    $response['success'] = false;
                    $response['response'] = $oldAnnuncio->getId();
                    return new JsonResponse($response);

                }


                $annuncio->setUtente($this->getUser());

                $board = $this->container->get('doctrine')
                    ->getRepository('CCDNForumForumBundle:Board')->find(11);

                $firstTopic = new Topic();


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


                $testoAnnuncio = "<u>DATI RICHIEDENTE</u>
        <br>
        <br>
        <i>
            <strong>RICHIESTA AVANZATA DA</strong>: ".$annuncio->getUtente()->getNome()."
            <br>
            <strong>E.MAIL</strong>: ".$annuncio->getUtente()->getEmail()."
            <br>
            <strong>RECAPITO TELEFONICO</strong>: ".$annuncio->getTelefono()."
            <br>
            <br>
        </i>
        <u>SPECIFICHE</u>
        <br>
        <br>
        <i>
            <strong>OFFRO IL MIO POSTO BARCA A</strong>: ".$annuncio->getLuogoAttuale()->getNome()."
            <br>
            <strong>E CERCO UN POSTO BARCA IN ZONA</strong>: ".$luogoCercato."
            <br>
            <strong>TIPOLOGIA DI BARCA</strong>: ".$annuncio->getTipo()."
            <br>
            <strong>POSTO LUNGHEZZA CIRCA</strong>: ".$annuncio->getLunghezza()."
            <br>
            <br>
        </i>
        <strong>
            <font style=\"color:green\">DATE E PERIODO</font>
        </strong>
        <font style=\"color:green\">: ".$annuncio->getTempo()."
            <br>
            <br>
        </font>
        <u>NOTE VARIE</u>
        <br>
        <br>
        <i>
            <i>".$annuncio->getDescrizione()."</i>
        </i>";


                $post->setBody($testoAnnuncio);

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


                $subscription = new \CCDNForum\ForumBundle\Entity\Subscription();
                $subscription->setTopic($firstTopic);
                $subscription->setOwnedBy($this->getUser());

                $forum = $this->container->get('doctrine')
                    ->getRepository('CCDNForumForumBundle:Forum')->find(1);

                $subscription->setForum($forum);

                $em->persist($subscription);
                $em->flush();

                $board->setCachedPostCount($board->getCachedPostCount() + 1);
                $board->setCachedTopicCount($board->getCachedTopicCount() + 1);

                $em->persist($board);
                $em->flush();







                //Create the Transport
                $transport = \Swift_MailTransport::newInstance();

//Create the Mailer using your created Transport
                $mailer = \Swift_Mailer::newInstance($transport);

                $messaggio = \Swift_Message::newInstance()
                    ->setSubject("Creato un nuovo topic: ".$firstTopic->getTitle())
                    ->setFrom('info@velaforfun.com')
                    ->setTo('velaforfun@velaforfun.com')
                    ->setBcc('christian1488@hotmail.it')
                    ->setBody(
                        $this->container->get('templating')->render(
                        // app/Resources/views/Emails/registrazione.html.twig
                            'Emails/nuovo_post.html.twig',
                            array('topic' => $firstTopic, 'post' => $post)
                        ),
                        'text/html'
                    );
                $mailer->send($messaggio);





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
     * @Route("/{page}", name="annunci_cambio_posto", requirements={"page": "\d+"},defaults={"page": 1})
     */
    public function annunciScambioPostoAction($page)
    {

        $query = $this->getDoctrine()
            ->getRepository('AppBundle:AnnuncioScambioPosto')->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        // No need to manually get get the result ($query->getResult())


        $annunci = $this->paginate($query, $page);
        $titolo = "Annunci Scambio Posto Barca";


        $form['vars'] = array("full_name" => "appbundle_annuncioscambioposto");

        foreach($annunci as $key=>$annuncio) {
            if ($annuncio->getTopic()->isDeleted() || $annuncio->getTopic()->isClosed()) {
               // unset($annunci[$key]);
            }
        }


        return $this->render(
            'AppBundle:AnnuncioScambioPosto:lista.html.twig',
            array("annunci" => $annunci, "titolo" => $titolo, "form" => $form,"pagine"=>ceil(count($annunci)/10),"currentPage"=>$page)
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

                if(!isset($params['appbundle_annuncioscambioposto']['luogoRicercato'])){
                    $params['appbundle_annuncioscambioposto']['luogoRicercato']="TUTTO";
                }

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


                    if(!$annuncio->getLuogoRicercato()){
                        $annuncio->setLuogoRicercato("TUTTO");
                    }
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


                    $testoAnnuncio = "<u>DATI RICHIEDENTE</u>
        <br>
        <br>
        <i>
            <strong>RICHIESTA AVANZATA DA</strong>: ".$annuncio->getUtente()->getNome()."
            <br>
            <strong>E.MAIL</strong>: ".$annuncio->getUtente()->getEmail()."
            <br>
            <strong>RECAPITO TELEFONICO</strong>: ".$annuncio->getTelefono()."
            <br>
            <br>
        </i>
        <u>SPECIFICHE</u>
        <br>
        <br>
        <i>
            <strong>OFFRO IL MIO POSTO BARCA A</strong>: ".$annuncio->getLuogoAttuale()->getNome()."
            <br>
            <strong>E CERCO UN POSTO BARCA IN ZONA</strong>: ".$luogoCercato."
            <br>
            <strong>TIPOLOGIA DI BARCA</strong>: ".$annuncio->getTipo()."
            <br>
            <strong>POSTO LUNGHEZZA CIRCA</strong>: ".$annuncio->getLunghezza()."
            <br>
            <br>
        </i>
        <strong>
            <font style=\"color:green\">DATE E PERIODO</font>
        </strong>
        <font style=\"color:green\">: ".$annuncio->getTempo()."
            <br>
            <br>
        </font>
        <u>NOTE VARIE</u>
        <br>
        <br>
        <i>
            <i>".$annuncio->getDescrizione()."</i>
        </i>";


                    $post->setBody($testoAnnuncio);

                    $em->persist($post);
                    $em->flush();


                    $annuncio->setTopic($firstTopic);
                    $em->persist($annuncio);
                    $em->flush();


                    $firstTopic->setFirstPost($post);
                    $firstTopic->setLastPost($post);
                    $em->merge($firstTopic);
                    $board->setLastPost($post);
                    $em->persist($board);
                    $em->flush();

                    $subscription = new \CCDNForum\ForumBundle\Entity\Subscription();
                    $subscription->setTopic($firstTopic);
                    $subscription->setOwnedBy($this->getUser());

                    $forum = $this->container->get('doctrine')
                        ->getRepository('CCDNForumForumBundle:Forum')->find(1);

                    $subscription->setForum($forum);

                    $em->persist($subscription);
                    $em->flush();

                    $board->setCachedPostCount($board->getCachedPostCount() + 1);
                    $board->setCachedTopicCount($board->getCachedTopicCount() + 1);

                    $em->persist($board);
                    $em->flush();



                } else {
                    die(var_dump($this->getErrorsAsArray($postform)));

                }

            }


            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:AnnuncioScambioPosto');
            $query = $repository->createQueryBuilder('p');
            $annunci = array();
            if ($params['appbundle_annuncioscambioposto']['luogoRicercato'] != "TUTTO") {

                $porto = $this->getDoctrine()
                    ->getRepository('AppBundle:Porto')->find(
                        $params['appbundle_annuncioscambioposto']['luogoRicercato']
                    );

                $annunci = $repository->findBy(array("luogoAttuale" => $porto), array("id" => "desc"));
            } else {
                $annunci = $repository->findBy(array(), array("id" => "desc"));
            }

            foreach($annunci as $key=>$annuncio) {
                if ($annuncio->getTopic()->isDeleted() || $annuncio->getTopic()->isClosed()) {
                    unset($annunci[$key]);
                }
            }

            return $this->render(
                'AppBundle:AnnuncioScambioPosto:cercaAjax.html.twig',
                array("annunci" => $annunci)
            );
        }

        return $this->render(
            'AppBundle:AnnuncioScambioPosto:cerca.html.twig',
            array("annunci" => $annunci, "form" => $postform->createView())
        );
    }

    private function allertaAnnunci($annuncio)
    {

        // CHI CREA INVIA A TUTTI QUELLI CHE ERANO IN ATTESA

        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:AnnuncioScambioPosto');
        $query = $repository->createQueryBuilder('p')
            ->where("p.tipoAnnuncio = 'CERCO'");
        $destinatari = [];
        if ($annuncio->getLuogoRicercato() != "TUTTO") {

            $porto = $this->getDoctrine()
                ->getRepository('AppBundle:Porto')->find($annuncio->getLuogoRicercato());

            $destinatari = $repository->findBy(array("luogoAttuale" => $porto), array("id" => "desc"));
        } else {
            $destinatari = $repository->findBy(array(), array("id" => "desc"));
        }


        foreach ($destinatari as $destinatario) {


            //Create the Transport
            $transport = \Swift_MailTransport::newInstance();

//Create the Mailer using your created Transport
            $mailer = \Swift_Mailer::newInstance($transport);

            $messaggio = \Swift_Message::newInstance()
                ->setSubject("Annuncio scambio posto")
                ->setFrom('info@velaforfun.com')
                ->setTo($destinatario->getEmail())
                ->setBcc('info@velaforfun.com')
                ->setBody(
                    $this->container->get('templating')->render(
                    // app/Resources/views/Emails/registrazione.html.twig
                        'Emails/annuncio_scambio_posto.html.twig',
                        array('annuncio' => $annuncio)
                    ),
                    'text/html'
                );

            $mailer->send($messaggio);

        }


    }

}
