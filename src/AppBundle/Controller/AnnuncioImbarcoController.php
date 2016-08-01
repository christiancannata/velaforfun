<?php

namespace AppBundle\Controller;

use AppBundle\Form\AnnuncioImbarcoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\Controller\Bar;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;


class AnnuncioImbarcoController extends BaseController
{
    protected $entity = "AnnuncioImbarco";

    /**
     * @Route( "crea", name="create_annuncio_imbarco" )
     * @Template()
     */
    public function createAction(Request $request)
    {
        return $this->postForm($request, new AnnuncioImbarcoType());
    }


    /**
     * @Route( "modifica/{id}", name="modifica_annuncio_imbarco" )
     * @Template()
     */
    public function patchAction(Request $request, $id)
    {
        return $this->patchForm($request, new AnnuncioImbarcoType(), $id, "AnnuncioImbarco");
    }


    /**
     * @Route("/", name="annunci_imbarco")
     */
    public function annunciImbarcoAction()
    {

        $annunci = $this->getDoctrine()
            ->getRepository('AppBundle:AnnuncioImbarco')->findBy(array("tipoAnnuncio"=>"OFFRO"), array('id' => 'desc'), 8);
        $titolo = "Annunci Imbarco";


        $form['vars'] = array("full_name" => "appbundle_annuncioimbarco");

        foreach($annunci as $key=>$annuncio) {
            if ($annuncio->getTopic()->isDeleted() || $annuncio->getTopic()->isClosed()) {
                unset($annunci[$key]);
            }
        }

        return $this->render(
            'AppBundle:AnnuncioImbarco:lista.html.twig',
            array("annunci" => $annunci, "titolo" => $titolo, "form" => $form)
        );

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
    public function eliminaAction(Request $request, $id)
    {
        return $this->delete($id);
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

                $annuncio = $postform->getData();
                $em = $this->container->get('doctrine')->getManager();

                $repository = $this->container->get('doctrine')
                    ->getRepository('AppBundle:User');

                $user = $this->getUser();



                if (!$user) {
                    $user = $repository->findOneBy(array("email" => $annuncio->getEmail()));
                    if (!$user) {
                        $userManager = $this->container->get('fos_user.user_manager');
                        $user = $userManager->createUser();
                        $user->setEmail($annuncio->getEmail());
                        $user->setNome($annuncio->getReferente());
                        $username = strtolower(str_replace(" ", "", $annuncio->getReferente().rand(0, 99)));
                        $user->setUsername($username);
                        $user->setPlainPassword($username."1");
                        $user->setEnabled(true);
                        $em->persist($user);
                        $em->flush();
                    }
                }




                $oldAnnuncio = $this->container->get('doctrine')
                    ->getRepository('AppBundle:AnnuncioImbarco')->findOneBy(array("title"=>$annuncio->getTitolo(),"user"=>$user));

                if($oldAnnuncio){
                    $response['success'] = true;
                    $response['response'] = $oldAnnuncio->getId();
                    return new JsonResponse($response);

                }


                $annuncio->setUtente($user);
                $titolo = str_replace("cerco", "", $annuncio->getTitolo());
                $titolo = str_replace("offro", "", $annuncio->getTitolo());

                $firstTopic = new Topic();
                $firstTopic->setTitle(nl2br($annuncio->getTipoAnnuncio())." ".$titolo);
                $firstTopic->setCachedViewCount(1);
                $board = null;

                $board = $this->container->get('doctrine')
                    ->getRepository('CCDNForumForumBundle:Board')->find(10);
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

                $testo="<strong>Ruolo richiesto:</strong> ".$annuncio->getRuoloRichiesto();
                $testo.="<br><br><strong>Luogo:</strong> ".$annuncio->getLuogo();


                if(trim($annuncio->getCosto())!=""){
                    $testo.="<br><br><strong>Prezzo:</strong> ".$annuncio->getCosto();

                }

                if(trim($annuncio->getTempo())!=""){
                    $testo.="<br><br><strong>Periodo:</strong> ".$annuncio->getTempo();

                }

                $testo.="<br><br><strong>Tipo:</strong> ".$annuncio->getTipo()."<br><br>";


                $testo.= nl2br($annuncio->getDescrizione());

                $post->setBody(
                   $testo
                );

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
                $subscription->setOwnedBy($user);

                $forum = $this->container->get('doctrine')
                    ->getRepository('CCDNForumForumBundle:Forum')->find(1);

                $subscription->setForum($forum);

                $em->persist($subscription);
                $em->flush();

                $board->setCachedPostCount($board->getCachedPostCount()+1);
                $board->setCachedTopicCount($board->getCachedTopicCount()+1);

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

        return $this->render('AppBundle:AnnuncioImbarco:crea.html.twig', array("form" => $postform->createView()));
    }


    /**
     * @Route( "cerca-annuncio", name="cerca_annuncio_imbarco" )
     * @Template()
     */
    public function cercaAnnuncioAction(Request $request)
    {


        $annunci = $this->getDoctrine()
            ->getRepository('AppBundle:AnnuncioImbarco')->findBy(array("tipoAnnuncio"=>"OFFRO"), array('id' => 'desc'), 8);

        $postform = $this->createForm(new AnnuncioImbarcoType());

        if ($request->isMethod('POST')) {
            $params = $request->request->all();
            if (isset($params['appbundle_annuncioimbarco']['notifica']) && $params['appbundle_annuncioimbarco']['notifica'] == 1) {
                unset($params['appbundle_annuncioimbarco']['notifica']);
                $request->request->set('appbundle_annuncioimbarco', $params['appbundle_annuncioimbarco']);
                $postform->handleRequest($request);
                if ($postform->isValid()) {
                    $annuncio = $postform->getData();
                    $em = $this->container->get('doctrine')->getManager();


                    $titolo = str_replace("cerco", "", $annuncio->getTitolo());
                    $titolo = str_replace("offro", "", $annuncio->getTitolo());

                  /*  $firstTopic = new Topic();
                    $firstTopic->setTitle(
                        $annuncio->getCosto()." ".$annuncio->getRuoloRichiesto()." in ".$annuncio->getTipo(
                        )." a ".$annuncio->getLuogo()
                    );
                    $firstTopic->setCachedViewCount(1);*/
                    $board = null;
                    $board = $this->container->get('doctrine')
                        ->getRepository('CCDNForumForumBundle:Board')->find(19);
                    $user = $this->getUser();
                    $annuncio->setReferente($user->getNome()." ".$user->getCognome()." ".$user->getUsername());

                    $annuncio->setReferente($user->getNome()." ".$user->getCognome()." ".$user->getUsername());

                    $annuncio->setEmail($user->getEmail());
                    $annuncio->setUtente($user);

                  /*  $firstTopic->setBoard(
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
*/

                    $annuncio->setDescrizione(
                        "Cerco ".$annuncio->getCosto()." ".$annuncio->getRuoloRichiesto()." in ".$annuncio->getTipo(
                        )." a ".$annuncio->getLuogo()
                    );

                    $annuncio->setTitolo( "Cerco ".$annuncio->getCosto()." ".$annuncio->getRuoloRichiesto()." in ".$annuncio->getTipo(
                        )." a ".$annuncio->getLuogo());


                    $annuncio->setTelefono("");
                    $annuncio->setLocalita("");
                    $annuncio->setTempo("");
                  /*  $post->setBody($annuncio->getDescrizione());

                    $em->persist($post);
                    $em->flush();


                    $annuncio->setTopic($firstTopic);*/


                    $annunciUtente = $this->getDoctrine()
                        ->getRepository('AppBundle:AnnuncioImbarco')->findBy(array("utente"=>$user,"tipoAnnuncio"=>"CERCO"));

                    foreach($annunciUtente as $annuncioUtente){
                        $em->remove($annuncioUtente);
                    }

                    $em->persist($annuncio);
                    $em->flush();





                    /* $firstTopic->setFirstPost($post);
                    $firstTopic->setLastPost($post);
                    $em->merge($firstTopic);
                    $board->setLastPost($post);
                    $em->persist($board);
                    $em->flush();
*/

                } else {
                    die(var_dump($this->getErrorsAsArray($postform)));

                }

            }

            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:AnnuncioImbarco');
            $query = $repository->createQueryBuilder('p')
                ->where("p.tipoAnnuncio = 'OFFRO'");
            if ($params['appbundle_annuncioimbarco']['luogo'] != "TUTTO") {
                $query->andWhere("p.luogo = '".$params['appbundle_annuncioimbarco']['luogo']."' or p.luogo='TUTTO'");

            }

            if ($params['appbundle_annuncioimbarco']['ruoloRichiesto'] != "TUTTO") {
                $query->andWhere("p.ruoloRichiesto = '".$params['appbundle_annuncioimbarco']['ruoloRichiesto']."' or p.ruoloRichiesto='TUTTO'");

            }
            if ($params['appbundle_annuncioimbarco']['costo'] != "TUTTO") {
                $query->andWhere("p.costo = '".$params['appbundle_annuncioimbarco']['costo']."' or p.costo='TUTTO'");

            }

            if ($params['appbundle_annuncioimbarco']['tipo'] != "TUTTO") {
                $query->andWhere("p.tipo = '".$params['appbundle_annuncioimbarco']['tipo']."' or p.tipo='TUTTO'");

            }
            $query->orderBy("p.id","DESC");

            $annunci = $query->getQuery()->getResult();

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
            'AppBundle:AnnuncioImbarco:cerca.html.twig',
            array("annunci" => $annunci, "form" => $postform->createView())
        );
    }

    private function allertaAnnunci($annuncio)
    {

        if ($annuncio->getTipoAnnuncio() == "OFFRO") {

            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:AnnuncioImbarco');
            $query = $repository->createQueryBuilder('p')
                ->where("p.tipoAnnuncio = 'CERCO'");
            if ($annuncio->getLuogo() != "TUTTO") {
                $query->andWhere("p.luogo = '".$annuncio->getLuogo()."' or p.luogo = 'TUTTO'");

            }

            if ($annuncio->getRuoloRichiesto() != "TUTTO") {
                $query->andWhere("p.ruoloRichiesto = '".$annuncio->getRuoloRichiesto()."'  or p.ruoloRichiesto = 'TUTTO'");

            }
            if ($annuncio->getCosto() != "TUTTO") {
                $query->andWhere("p.costo = '".$annuncio->getCosto()."'  or p.costo = 'TUTTO'");

            }


            $destinatari = $query->getQuery()->getResult();




            foreach ($destinatari as $destinatario) {
                $mailer = $this->container->get('mailer');
                $messaggio = $mailer->createMessage()
                    ->setSubject("Annuncio imbarco [".$annuncio->getLocalita()."] - ".$annuncio->getRuoloRichiesto())
                    ->setFrom('info@velaforfun.com')
                    ->setTo($destinatario->getEmail())
                    ->setBcc('info@velaforfun.com')
                    ->setBody(
                        $this->container->get('templating')->render(
                        // app/Resources/views/Emails/registrazione.html.twig
                            'Emails/annuncio_imbarco.html.twig',
                            array('annuncio' => $annuncio)
                        ),
                        'text/html'
                    );
                $mailer->send($messaggio);

            }

        }


    }
}
