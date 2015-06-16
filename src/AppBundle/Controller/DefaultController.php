<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use BlogBundle\Entity\Articolo;
use BlogBundle\Form\ArticoloType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\RegistrationFormType;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {

        $repository = $this->getDoctrine()
            ->getRepository('BlogBundle:Articolo');

        $articoli = $repository->findByStato("ATTIVO", array('id' => 'desc'));


        return $this->render('default/index.html.twig', array("articoli" => $articoli));
    }


    /**
     * @Route("/chi-siamo", name="chi_siamo")
     */
    public function chiSiamoAction()
    {

        return $this->render('default/chi-siamo.html.twig', array());
    }


    /**
     * @Route("/ricette-da-cambusa", name="ricette")
     */
    public function ricetteAction()
    {

        $repository = $this->getDoctrine()->getManager()->getRepository('BlogBundle:Articolo');
        $query = $repository->createQueryBuilder('p')
            ->where('p.categoria in(11,12,13,14,15)')
            ->getQuery();
        $articoli = $query->getResult();


        return $this->render('default/ricette.html.twig', array("articoli" => $articoli));
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


            $testo = "Tempo: ".$params['blogbundle_articolo']['tempo'];
            $testo .= "<br><br>Persone: ".$params['blogbundle_articolo']['persone'];
            $testo .= "<br><br>Ingredienti ".$params['blogbundle_articolo']['ingredienti'];
            $testo .= "<br><br>Ricetta: ".$params['blogbundle_articolo']['ricetta'];

            $articolo->setTesto($testo);


            if ($this->getUser()) {
                $autore = $this->getUser();
            } else {
                $autore = $this->container->get('doctrine')
                    ->getRepository('AppBundle:User')->findOneByUsername($params['blogbundle_articolo']['autore']);

                if (!$autore) {
                    $userManager = $this->getContainer()->get('fos_user.user_manager');
                    $autore = $userManager->createUser();
                    $autore->setEmail($params['blogbundle_articolo']['autore']);
                    $autore->setNome($params['blogbundle_articolo']['email']);
                    $username = strtolower(str_replace(" ", "", $params['blogbundle_articolo']['autore']));
                    $autore->setUsername($username);
                    $autore->setPlainPassword($username."1");

                    $this->em->persist($autore);
                }
            }

            $articolo->setAutore(
                $autore
            );


            $articolo->setCategoria(
                $this->container->get('doctrine')
                    ->getRepository('BlogBundle:Categoria')->find($params['blogbundle_articolo']['categoria'])
            );


            $files=$request->files->all();

            $articolo->setProfilePictureFile(
                $files['blogbundle_articolo']['profilePictureFile']
            );




            $em = $this->container->get('doctrine')->getManager();

            $em->persist($articolo);
            $em->flush();

            $response['success'] = true;
            $response['response'] = $articolo->getId();

            return new JsonResponse($response);
        }

        return $this->render('default/crea-ricetta.html.twig', array("form" => $postform->createView()));
    }


    /**
     * @Route("/privacy", name="privacy")
     */
    public function privacyAction()
    {
        return $this->render('default/privacy.html.twig', array());
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
                ->setTo('christian1488@hotmail.it')
                ->setBody(
                    $this->container->get('templating')->render(
                    // app/Resources/views/Emails/registrazione.html.twig
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

        return $this->render('default/contatti.html.twig', array("form" => $form));
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
                        "link" => "/foto/".$annuncio->getGalleria()->getPermalink()
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
                        "link" => "/video/".$annuncio->getCategoria()->getPermalink()
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


        return $this->render('default/cerca.html.twig', array("key" => $key, "risultati" => $risultati));
    }


    /**
     * @Route("/{permalink}", name="pagine_statiche")
     */
    public function pagineStaticheAction($permalink)
    {

        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:PaginaStatica');
        $articoli = $repository->findOneByPermalink($permalink);

        if (!$articoli) {


            throw $this->createNotFoundException('Unable to find Page.');
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
