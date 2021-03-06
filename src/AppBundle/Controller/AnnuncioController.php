<?php

namespace AppBundle\Controller;

use AppBundle\Form\AnnuncioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\Bundle\TestBundle\Controller\Bar;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CCDNForum\ForumBundle\Entity\Post;
use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Board;
use CCDNForum\ForumBundle\Entity\Subscription;


class AnnuncioController extends BaseController
{
    protected $entity = "Annuncio";

    /**
     * @Route( "nuovo-annuncio", name="crea_nuovo_annuncio" )
     * @Template()
     */
    public function nuovoAnnuncioAction(Request $request)
    {
        $postform = $this->createForm(new AnnuncioType());

        if ($request->isMethod('POST')) {

            $postform->handleRequest($request);

            if ($postform->isValid()) {

                $annuncio = $postform->getData();

                $user = $this->getUser();

                $repository = $this->container->get('doctrine')
                    ->getRepository('AppBundle:User');
                $em = $this->container->get('doctrine')->getManager();

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

                try{
                    $oldAnnuncio = $this->getDoctrine()
                        ->getRepository('AppBundle:Annuncio')->findOneBy(array("titolo"=>$annuncio->getTitolo(),"referente"=>$annuncio->getReferente()));

                    if($oldAnnuncio){

                        $response['success'] = false;
                        $response['response'] = $oldAnnuncio->getId();
                        return new JsonResponse($response);

                    }
                }catch(\Exception $e){
                    die(var_dump($e->getTraceAsString()));
                }







                if($annuncio->getTipo()=="VENDO"){
                    $board = $this->container->get('doctrine')
                        ->getRepository('CCDNForumForumBundle:Board')->find(18);
                }else{
                    $board = $this->container->get('doctrine')
                        ->getRepository('CCDNForumForumBundle:Board')->find(17);

                }






                $firstTopic = new Topic();


                $firstTopic->setTitle(
                    $annuncio->getTitolo()
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
                    $user
                );


                $post->setBody(nl2br($annuncio->getDescrizione())."<br><br>Recapito: ".$annuncio->getTelefono());

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


                $subscription=new Subscription();
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

                $messaggio = \Swift_Message::newInstance("Creato un nuovo topic: ".$firstTopic->getTitle())
                    ->setFrom('info@velaforfun.com')
                    ->setTo('velaforfun@velaforfun.com')
                    ->setBcc('christiancannata@gmail.com')
                    ->setBody(
                        $this->container->get('templating')->render(
                        // app/Resources/views/Emails/registrazione.html.twig
                            'Emails/nuovo_post.html.twig',
                            array('topic' => $firstTopic, 'post' => $post)
                        ),
                        'text/html'
                    );
                $response['success'] =false;

                if($mailer->send($messaggio)){
                    $response['success'] = true;
                }
                $response['response'] = $firstTopic->getId();


            } else {
                $response['success'] = false;


                $response['reponse'] = $this->getErrorsAsArray($postform);

            }

            return new JsonResponse($response);
        }

        return $this->render('AppBundle:AnnuncioScambioPosto:crea.html.twig', array("form" => $postform->createView(),"title"=>"Nuovo annuncio compro/vendo"));
    }


    /**
     * @Route( "modifica/{id}", name="modifica_annuncio" )
     * @Template()
     */
    public function patchAction(Request $request, $id)
    {
        return $this->patchForm($request, new AnnuncioType(), $id, "Annuncio");
    }


    /**
     * @Route( "list", name="list_annuncio" )
     * @Template()
     */
    public function listAction(Request $request)
    {
        return $this->cGet();
    }


    /**
     * @Route( "elimina/{id}", name="delete_annuncio" )
     * @Template()
     */
    public function eliminaAction(Request $request, $id)
    {
        return $this->delete($id);
    }



    /**
     * @Route( "cerca-annuncio", name="cerca_annuncio" )
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

                    $luogoCercato=ucwords(str_replace("_"," ",strtolower($annuncio->getLuogoRicercato())));

                    $annuncio->setUtente($user);
                    $annuncio->setDescrizione("Scambio posto a ".$annuncio->getLuogoAttuale()->getNome()." con ".$luogoCercato);
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

            $annunci = $this->getDoctrine()
                ->getRepository('AppBundle:AnnuncioScambioPosto')->findBy(
                    array(
                        "luogoAttuale" => $params['appbundle_annuncioscambioposto']['luogoRicercato']
                    ),
                    array('id' => 'desc')
                );


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

    }


    /**
     * @Route("/{page}", name="annunci_home", requirements={"page": "\d+"},defaults={"page": 1})
     */

    public function annunciAction($page)
    {


        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $qb->select(array('a'))
            ->from('\\AppBundle\\Entity\\Annuncio', 'a')
            ->join('a.topic', 'c')
        ->where("c.isDeleted=false and c.isClosed=false ")
        ->orderBy("c.id","desc");

        $query = $qb->getQuery();

        // No need to manually get get the result ($query->getResult())


        $annunci = $this->paginate($query, $page);


        $titolo = "Annunci Vendo/Compro";


        $form['vars'] = array("full_name" => "appbundle_annuncio");


        return $this->render(
            'AppBundle:Annuncio:lista.html.twig',
            array("annunci" => $annunci, "titolo" => $titolo,"form" => $form,"pagine"=>ceil(count($annunci)/10),"currentPage"=>$page)
        );

    }
}
