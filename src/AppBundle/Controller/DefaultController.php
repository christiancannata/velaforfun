<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PhpImap\Mailbox as MailBox;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {

        $repository = $this->getDoctrine()
            ->getRepository('BlogBundle:Articolo');

        $articoli=$repository->findAll();


        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Segnale');

        $segnali=$repository->findAll();


        $mailbox = new Mailbox('{imap.gmail.com:993/imap/ssl}INBOX', 'christian.cannata@facile.it', 'zipzap91', __DIR__);
        $mails = array();

        $mailsIds = $mailbox->searchMailBox('ALL');
        if(!$mailsIds) {
            die('Mailbox is empty');
        }else{
            var_dump(count($mailsIds));
        }

       // $mailId = reset($mailsIds);
       // $mail = $mailbox->getMail($mailId);



        return $this->render('default/index.html.twig',array("articoli"=>$articoli,"segnali"=>$segnali));
    }
}
