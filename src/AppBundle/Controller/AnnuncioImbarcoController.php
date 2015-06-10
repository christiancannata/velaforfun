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
            ->getRepository('AppBundle:AnnuncioImbarco')->findAll();
        $titolo = "Annunci Imbarco";

        return $this->render(
            'AppBundle:AnnuncioImbarco:lista.html.twig',
            array("annunci" => $annunci, "titolo" => $titolo)
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


                $annuncio->setUtente($user);
                $titolo = str_replace("cerco", "", $annuncio->getTitolo());
                $titolo = str_replace("offro", "", $annuncio->getTitolo());

                $firstTopic = new Topic();
                $firstTopic->setTitle($annuncio->getTipoAnnuncio()." ".$titolo);
                $firstTopic->setCachedViewCount(1);
                $board = null;
                if ($annuncio->getTipoAnnuncio() == "CERCO") {
                    $board = $this->container->get('doctrine')
                        ->getRepository('CCDNForumForumBundle:Board')->find(19);

                } else {
                    $board = $this->container->get('doctrine')
                        ->getRepository('CCDNForumForumBundle:Board')->find(20);
                }
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


                $response['success'] = true;
                $response['response'] = $firstTopic->getId();


            } else {
                $response['success'] = false;


                $response['reponse'] = $this->getErrorsAsArray($postform);

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
            ->getRepository('AppBundle:AnnuncioImbarco')->findAll();

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

                    $firstTopic = new Topic();
                    $firstTopic->setTitle(
                        $annuncio->getCosto()." ".$annuncio->getRuoloRichiesto()." in ".$annuncio->getTipo(
                        )." a ".$annuncio->getLuogo()
                    );
                    $firstTopic->setCachedViewCount(1);
                    $board = null;
                    if ($annuncio->getTipoAnnuncio() == "OFFRO") {
                        $board = $this->container->get('doctrine')
                            ->getRepository('CCDNForumForumBundle:Board')->find(19);
                        $user = $this->getUser();
                        $annuncio->setReferente($user->getNome()." ".$user->getCognome()." ".$user->getUsername());

                    } else {

                        $user = $this->container->get('doctrine')
                            ->getRepository('AppBundle:User')->findByEmail($annuncio->getEmail());

                        if (!$user) {

                            $userManager = $this->getContainer()->get('fos_user.user_manager');
                            $user = $userManager->createUser();
                            $user->setEmail($annuncio->getEmail());
                            $user->setNome($annuncio->getReferente());
                            $username = strtolower(str_replace(" ", "", $annuncio->getEmail())).rand(0, 99);
                            $user->setUsername($username);
                            $user->setPlainPassword($username."1");

                            $em->persist($user);
                            $em->flush();
                        }


                    }
                    $annuncio->setReferente($user->getNome()." ".$user->getCognome()." ".$user->getUsername());

                    $annuncio->setEmail($user->getEmail());
                    $annuncio->setUtente($user);

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


                    $annuncio->setDescrizione(
                        "Offro ".$annuncio->getCosto()." ".$annuncio->getRuoloRichiesto()." in ".$annuncio->getTipo(
                        )." a ".$annuncio->getLuogo()
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
                ->getRepository('AppBundle:AnnuncioImbarco')->findBy(
                    array(
                        "tipoAnnuncio" => "OFFRO",
                        "luogo" => $params['appbundle_annuncioimbarco']['luogo'],
                        "ruoloRichiesto" => $params['appbundle_annuncioimbarco']['ruoloRichiesto'],
                        "costo" => $params['appbundle_annuncioimbarco']['costo']
                    ),
                    array('id' => 'desc')
                );


            $responseJson = array();
            foreach ($annunci as $annuncio) {
                $responseJson[] = array(
                    "topic" => array(
                        "id" => $annuncio->getTopic()->getId(),
                        "title" => $annuncio->getTopic()->getTitle()
                    ),
                    "timestamp" => $annuncio->getTimestamp()->format("d-m-Y H:i"),

                );
            }

            return new JsonResponse($responseJson);
        }

        return $this->render(
            'AppBundle:AnnuncioImbarco:cerca.html.twig',
            array("annunci" => $annunci, "form" => $postform->createView())
        );
    }

    private function allertaAnnunci($annuncio)
    {

        if ($annuncio->getTipoAnnuncio() == "CERCO") {

            $annunci = $this->getDoctrine()
                ->getRepository('AppBundle:AnnuncioImbarco')->findBy(
                    array(
                        "tipoAnnuncio" => "OFFRO",
                        "ruoloRichiesto" => $annuncio->getRuoloRichiesto(),
                        "luogo" => $annuncio->getLuogo()
                    ),
                    array('id' => 'desc')
                );
            foreach ($annunci as $annuncio) {

                $mailer = $this->getContainer()->get('mailer');
                $messaggio = $mailer->createMessage()
                    ->setSubject('Ciao')
                    ->setFrom('mittente@example.com')
                    ->setTo($annuncio->getUtente()->getEmail())
                    ->setBody(
                        $this->renderView(
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
