<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Newsletter\Subscriber;
use AppBundle\Entity\User;
use BlogBundle\Entity\Articolo;
use BlogBundle\Form\ArticoloType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\Type\RegistrationFormType;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;


class DefaultController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()
            ->getRepository('BlogBundle:Articolo');

        $articoli = $repository->findByStato("ATTIVO", array('timestamp' => 'desc'), 4);


        $repository = $this->getDoctrine()
            ->getRepository('CCDNForumForumBundle:Topic');

        $post = $repository->findBy(array(), array('id' => 'desc'), 20);


        return $this->render('default/index.html.twig', array("articoli" => $articoli, "ultimiPost" => $post));
    }


    /**
     * @Route("/sitemap.{_format}", name="sample_sitemaps_sitemap", Requirements={"_format" = "xml"})
     */
    public function sitemapAction()
    {

        $repository = $this->getDoctrine()
            ->getRepository('BlogBundle:Articolo');

        $articoli = $repository->findByStato("ATTIVO");

        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Porto');

        $porti = $repository->findAll();


        $repository = $this->getDoctrine()
            ->getRepository('CCDNForumForumBundle:Topic');

        $post = $repository->findAll();


        $urls = array();
        $hostname = $this->getRequest()->getHost();

        // add some urls homepage
        $urls[] = array(
            'loc' => $this->get('router')->generate('homepage'),
            'changefreq' => 'weekly',
            'priority' => '1.0'
        );
        $urls[] = array(
            'loc' => $this->get('router')->generate('homepage_archivio'),
            'changefreq' => 'weekly',
            'priority' => '1.0'
        );
        $urls[] = array(
            'loc' => $this->get('router')->generate('chi_siamo'),
            'changefreq' => 'weekly',
            'priority' => '1.0'
        );
        $urls[] = array(
            'loc' => $this->get('router')->generate('ricette'),
            'changefreq' => 'weekly',
            'priority' => '1.0'
        );
        $urls[] = array(
            'loc' => $this->get('router')->generate('crea_ricetta'),
            'changefreq' => 'weekly',
            'priority' => '1.0'
        );
        $urls[] = array(
            'loc' => $this->get('router')->generate('contatti'),
            'changefreq' => 'weekly',
            'priority' => '1.0'
        );


        foreach ($articoli as $product) {
            $urls[] = array(
                'loc' => "/".$product->getCategoria()->getPermalink()."/".$product->getPermalink(),
                'priority' => '0.5'
            );
        }


        foreach ($porti as $product) {
            $urls[] = array('loc' => "/porti/".$product->getPermalink(), 'priority' => '0.5');
        }

        foreach ($post as $product) {
            $urls[] = array('loc' => "/forum/velaforfun/topic/".$product->getId(), 'priority' => '0.5');
        }


        return $this->render(
            'default/sitemap.xml.twig',
            array('urls' => $urls, 'hostname' => $hostname)
        );


    }


    /**
     * @Route("/archivio", name="homepage_archivio")
     */
    public function archivioAction()
    {
        $categorie = $this->getDoctrine()
            ->getRepository('BlogBundle:Categoria')->findAll();

        $ultimiArticoli = $this->getDoctrine()
            ->getRepository('BlogBundle:Articolo')->findAll(array("id" => "desc"), 10);

        return $this->render(
            'BlogBundle:Default:index.html.twig',
            array('categorie' => $categorie, 'articoli' => $ultimiArticoli)
        );
    }


    /**
     * @Route("/recupera-comunicati", name="recupera_comunicati")
     */
    public function recuperaComunicatiAction(Request $request)
    {

        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);
        if ($request->request->get("limit")) {
            $limit = $request->request->get("limit");
        }
        $input = new ArrayInput(
            array(
                'command' => 'app:check-email',
                '--limit' => $limit
            )
        );
        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);

        // return the output, don't use if you used NullOutput()
        $content = $output->fetch();

        // return new Response(""), if you used NullOutput()
        return new Response($content);
    }


    /**
     * @Route("/chi-siamo", name="chi_siamo")
     */
    public function chiSiamoAction()
    {

        $repository = $this->getDoctrine()
            ->getRepository('BlogBundle:Articolo');

        $articoli = $repository->findByStato("ATTIVO", array('id' => 'desc'), 4);


        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:PaginaStatica');


        $paginaStatica = null;

        return $this->render(
            'default/chi-siamo.html.twig',
            array("articoli" => $articoli, "paginaStatica" => $paginaStatica)
        );
    }


    /**
     * @Route("/ricette-da-cambusa", name="ricette")
     */
    public function ricetteAction()
    {

        $repository = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Articolo');
        $query = $repository->createQueryBuilder('p')
            ->where('p.categoria in(11,12,13,14,15)')
            ->orderBy("p.id", "desc")
            ->getQuery();
        $articoli = $query->getResult();


        return $this->render('default/ricette.html.twig', array("articoli" => $articoli));
    }

    /**
     * @Route("/forum-post/{id}", name="post_jsondata")
     */
    public function postGetAction($id)
    {

        $connection = $this->getDoctrine()->getManager()->getConnection();
        $statement = $connection->prepare("SELECT * FROM cc_forum_post WHERE id = :id limit 1");
        $statement->bindValue('id', $id);
        $statement->execute();
        $results = $statement->fetchAll();

        $user = $this->container->get('doctrine')
            ->getRepository('AppBundle:User')->find($results[0]['fk_created_by_user_id']);

        if (count($results) > 0) {
            return $this->render('default/post-forum-partial.html.twig', array("post" => $results[0], "user" => $user));

        }


    }


    /**
     * @Route("/nuova-ricetta", name="crea_ricetta")
     */
    public function creaRicettaAction(Request $request)
    {


        $postform = $this->createForm(new ArticoloType());

        if ($request->isMethod('POST')) {


            $params = $request->request->all();

            $articolo = new Articolo();


            $articolo->setTitolo($params['blogbundle_articolo']['titolo']);

            $ingredienti = explode(",", $params['blogbundle_articolo']['ingredienti']);
            $strIngredienti = "<ul class='ingrendienti'>";
            foreach ($ingredienti as $ingrediente) {
                $strIngredienti .= "<li>".trim($ingrediente)."</li>";
            }
            $strIngredienti .= "</ul>";
            $testo = "Tempo: ".$params['blogbundle_articolo']['tempo'];
            $testo .= "<br><br>Ricetta per ".$params['blogbundle_articolo']['persone']." persone";
            $testo .= "<br><br><strong>Ingredienti:</strong><br> ".$strIngredienti;

            $articolo->setSottotitolo("");

            $testo .= "<br><br><strong>Ricetta:</strong> ".$params['blogbundle_articolo']['ricetta'];


            $testo .= "<br><br><strong>Scritta da:</strong> ".$params['blogbundle_articolo']['autore'];


            $articolo->setTesto($testo);

            $em = $this->container->get('doctrine')->getManager();

            if ($this->getUser()) {
                $autore = $this->getUser();
            } else {
                $autore = $this->container->get('doctrine')
                    ->getRepository('AppBundle:User')->findOneByUsername($params['blogbundle_articolo']['autore']);

                if (!$autore) {
                    $userManager = $this->container->get('fos_user.user_manager');
                    $autore = $userManager->createUser();
                    $autore->setEmail($params['blogbundle_articolo']['autore']);
                    $autore->setNome($params['blogbundle_articolo']['email']);
                    $username = strtolower(str_replace(" ", "", $params['blogbundle_articolo']['autore']));
                    $autore->setUsername($username);
                    $autore->setPlainPassword($username."1");

                    $em->persist($autore);
                }
            }

            $articolo->setStato("ATTIVO");
            $articolo->setAutore(
                $autore
            );


            $articolo->setCategoria(
                $this->container->get('doctrine')
                    ->getRepository('BlogBundle:Categoria')->find($params['blogbundle_articolo']['categoria'])
            );


            $files = $request->files->all();

            $articolo->setProfilePictureFile(
                $files['blogbundle_articolo']['profilePictureFile']
            );


            $em = $this->container->get('doctrine')->getManager();

            $em->persist($articolo);
            $em->flush();

            $response['success'] = true;
            $response['response'] = $articolo->getCategoria()->getPermalink()."/".$articolo->getPermalink();

            return new JsonResponse($response);
        }

        return $this->render('default/crea-ricetta.html.twig', array("form" => $postform->createView()));
    }


    /**
     * @Route("/privacy", name="privacy")
     */
    public function privacyAction()
    {
        return $this->redirect("https://www.iubenda.com/privacy-policy/885486", 301);
    }

    /**
     * @Route("/email/{layout}", name="layout")
     */
    public function emailAction($layout)
    {
        return $this->render('Emails/'.$layout.'.html.twig', array());
    }


    /**
     * @Route("/profilo/tuoi-annunci", name="tuoi_annunci")
     */
    public function tuoiAnnunciAction()
    {

        $annunciImbarco = $repository = $this->getDoctrine()
            ->getRepository('AppBundle:AnnuncioImbarco')->findByUtente($this->getUser());

        $annunciScambio = $repository = $this->getDoctrine()
            ->getRepository('AppBundle:AnnuncioScambioPosto')->findByUtente($this->getUser());

        return $this->render(
            'default/tuoi-annunci.html.twig',
            array("annunciImbarco" => $annunciImbarco, "annunciScambio" => $annunciScambio)
        );
    }


    /**
     * @Route("/contatti", name="contatti")
     */
    public function contattiAction(Request $request)
    {


        if ($request->isMethod('POST')) {

            $params = $request->request->all();


            $mailer = $this->container->get('mailer');

            $messaggio = $mailer->createMessage()
                ->setSubject('Nuova richiesta di contatto')
                ->setFrom('info@velaforfun.com')
                ->setTo('info@velaforfun.com')
                ->setBcc('christiancannata@gmail.com')
                ->setBody(
                    $this->container->get('templating')->render(
                        'Emails/richiesta_contatto.html.twig',
                        array('contatto' => $params['contatto'])
                    ),
                    'text/html'
                );
            $mailer->send($messaggio);


            $response['success'] = true;


            return new JsonResponse($response);
        }

        $form['vars'] = array("full_name" => "contatti");


        $repository = $this->getDoctrine()
            ->getRepository('BlogBundle:Articolo');

        $articoli = $repository->findByStato("ATTIVO", array('id' => 'desc'), 4);


        return $this->render('default/contatti.html.twig', array("form" => $form, "articoli" => $articoli));
    }

    /**
     * @Route("/tuo-profilo/modifica-dati", name="modifica_dati")
     */
    public function modificaDatiAction(Request $request)
    {
        $postform = $this->createForm(new RegistrationFormType(), $this->getUser());

        if ($request->isMethod('POST')) {

            $postform->handleRequest($request);

            if ($postform->isValid()) {


                /*
                 * $data['title']
                 * $data['body']
                 */
                $em = $this->getDoctrine()->getManager();

                $em->flush();


                $response['success'] = true;

            } else {

                $response['success'] = false;
                $response['cause'] = $postform->getErrors();

            }

            return new JsonResponse($response);
        }

        return $this->render(
            'default/modifica-dati.html.twig',
            array('form' => $postform->createView())
        );
    }


    /**
     * @Route("/faq", name="faq")
     */
    public function faqAction()
    {

        return $this->render('default/faq.html.twig', array());
    }


    /**
     * @Route("/cerca", name="cerca")
     */
    public function cercaAction(Request $request)
    {
        $key = $request->get("key");

        $risultati = array();

        if ($key != "") {

            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Porto');
            $query = $repository->createQueryBuilder('p')
                ->where('p.nome LIKE :word')
                ->setParameter('word', '%'.$key.'%')
                ->getQuery();
            $porti = $query->getResult();

            if (count($porti) > 0) {
                $rows = array();
                foreach ($porti as $porto) {
                    $rows[] = array(
                        "name" => $porto->getNome(),
                        "link" => "/porti/".$porto->getPermalink()
                    );
                }
                $appo = array(
                    "type" => "Porti",
                    "results" => $rows
                );
                $risultati[] = $appo;
            }


            $repository = $this->getDoctrine()->getManager()->getRepository('CCDNForumForumBundle:Topic');
            $query = $repository->createQueryBuilder('p')
                ->where('p.title LIKE :word')
                ->setParameter('word', '%'.$key.'%')
                ->getQuery();
            $annunci = $query->getResult();

            if (count($annunci) > 0) {
                $rows = array();
                foreach ($annunci as $annuncio) {
                    $rows[] = array(
                        "name" => $annuncio->getTitle(),
                        "link" => "/forum/velaforfun/topic/".$annuncio->getId()
                    );
                }
                $appo = array(
                    "type" => "Forum",
                    "results" => $rows
                );
                $risultati[] = $appo;
            }


            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Nodo');
            $query = $repository->createQueryBuilder('p')
                ->where('p.nome LIKE :word')
                ->setParameter('word', '%'.$key.'%')
                ->getQuery();
            $annunci = $query->getResult();

            if (count($annunci) > 0) {
                $rows = array();
                foreach ($annunci as $annuncio) {
                    $rows[] = array(
                        "name" => $annuncio->getNome(),
                        "link" => "/nodi/".$annuncio->getPermalink()
                    );
                }
                $appo = array(
                    "type" => "Nodi",
                    "results" => $rows
                );
                $risultati[] = $appo;
            }


            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Foto');
            $query = $repository->createQueryBuilder('p')
                ->where('p.nome LIKE :word')
                ->setParameter('word', '%'.$key.'%')
                ->getQuery();
            $annunci = $query->getResult();

            if (count($annunci) > 0) {
                $rows = array();
                foreach ($annunci as $annuncio) {
                    $rows[] = array(
                        "name" => $annuncio->getNome(),
                        "link" => "/foto/".$annuncio->getGalleria()->getPermalink()."?foto=".$annuncio->getId()
                    );
                }
                $appo = array(
                    "type" => "Foto",
                    "results" => $rows
                );
                $risultati[] = $appo;
            }


            $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Video');
            $query = $repository->createQueryBuilder('p')
                ->where('p.nome LIKE :word')
                ->setParameter('word', '%'.$key.'%')
                ->getQuery();
            $annunci = $query->getResult();

            if (count($annunci) > 0) {
                $rows = array();
                foreach ($annunci as $annuncio) {
                    $rows[] = array(
                        "name" => $annuncio->getNome(),
                        "link" => "/video/".$annuncio->getCategoria()->getPermalink()."?video=".$annuncio->getId()
                    );
                }
                $appo = array(
                    "type" => "Video",
                    "results" => $rows
                );
                $risultati[] = $appo;
            }


            $repository = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Articolo');
            $query = $repository->createQueryBuilder('p')
                ->where('p.titolo LIKE :word')
                ->orWhere('p.sottotitolo LIKE :word')
                ->setParameter('word', '%'.$key.'%')
                ->getQuery();
            $annunci = $query->getResult();

            if (count($annunci) > 0) {
                $rows = array();
                foreach ($annunci as $annuncio) {
                    $rows[] = array(
                        "name" => $annuncio->getTitolo(),
                        "link" => "/archivio/".$annuncio->getCategoria()->getPermalink()."/".$annuncio->getPermalink()
                    );
                }
                $appo = array(
                    "type" => "Articoli",
                    "results" => $rows
                );
                $risultati[] = $appo;
            }


        }


        $repository = $this->getDoctrine()
            ->getRepository('BlogBundle:Articolo');

        $articoli = $repository->findByStato("ATTIVO", array('id' => 'desc'), 3);


        return $this->render(
            'default/cerca.html.twig',
            array("key" => $key, "risultati" => $risultati, "articoli" => $articoli)
        );
    }

    /**
     * @Route("/iscritti-newsletter", name="iscriviti_newsletter")
     */
    public function iscrivitiNewsletterAction(Request $request)
    {


        if ($request->isMethod('POST')) {
            $params = $request->request->all();

            $repository = $this->getDoctrine()
                ->getRepository('AppBundle\Entity\Newsletter\Subscriber');
            $iscritto = $repository->findOneByEmail($params['email']);


            if (!$iscritto) {
                $repository = $this->getDoctrine()
                    ->getRepository('AppBundle\Entity\Newsletter\Mandant');
                $mandant = $repository->find(1);

                $iscrizione = new Subscriber();
                $iscrizione->setMandant($mandant);
                $iscrizione->setLocale("it");
                $iscrizione->setEmail($params['email']);
                $iscrizione->setFirstName($params['nome']);
                $iscrizione->setLastName("");
                $iscrizione->setGender("MALE");
                $iscrizione->setCompanyname("");
                $iscrizione->setTitle("");
                $iscrizione->addGroup(
                    $this->getDoctrine()
                        ->getRepository('AppBundle\Entity\Newsletter\Group')->find(1)
                );


                $em = $this->container->get('doctrine')->getManager();

                $em->persist($iscrizione);
                $em->flush();

                $response['success'] = true;
                $response['response'] = "ok";

            } else {
                $response['success'] = false;
                $response['response'] = "ko";
            }

            return new JsonResponse($response);
        }

    }


    /**
     * @Route("/{permalink}", name="pagine_statiche")
     */
    public function pagineStaticheAction($permalink)
    {

        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:PaginaStatica');
        $articoli = $repository->findOneBy(array("permalink" => $permalink, "isActive" => true));

        if (!$articoli) {


            return $this->redirect('/');
        }


        return $this->render(
            'default/static.html.twig',
            array(
                "content" => $articoli->getContent(),
                "titolo" => $articoli->getTitolo(),
                "descrizione" => $articoli->getDescrizione()
            )
        );
    }


}
