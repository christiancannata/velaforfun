<?php
namespace AppBundle\Command;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckEmailCommand extends ContainerAwareCommand
{
    protected $output;
    protected $em;
    protected $connection;

    protected function configure()
    {
        $this
            ->setName('app:check-email')
            ->setDescription('Controlla email comunicati');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $mailbox = new Mailbox(
            '{imap.gmail.com:993/imap/ssl}INBOX',
            'comunicati@velaforfun.com ',
            'comunicati01 ',
            __DIR__
        );
        $mails = array();

        $mailsIds = $mailbox->searchMailBox('ALL');
        $repositoryArticolo = $this->getDoctrine()
            ->getRepository('BlogBundle:Articolo');
        if (!$mailsIds) {
            die('Mailbox is empty');
        } else {

            $mailsIds = $mailbox->sortMails();
            $mailsIds = array_slice($mailsIds, 4);
            foreach ($mailsIds as $mailId) {
                $mail = $mailbox->getMail($mailId);
                $emailComunicato = $repositoryArticolo->findOneBy(array("idComunicato" => $mail->id));
                if (!$emailComunicato) {

                    $articolo = new Articolo();
                    $articolo->setIdComunicato($mail->id);
                    $articolo->setTitolo($mail->subject);
                    $articolo->setTesto($mail->textHtml);
                    $articolo->generatePermalink($mail->subject);
                    $articolo->setCategoria(
                        $this->getDoctrine()
                            ->getRepository('BlogBundle:Categoria')->find(2)
                    );
                    $this->em->persist($articolo);
                    $repository = $this->getDoctrine()
                        ->getRepository('AppBundle:User');
                    $user = $repository->findOneBy(array("email" => $mail->fromAddress));
                    if (!$user) {
                        $userManager = $this->getContainer()->get('fos_user.user_manager');
                        $user = $userManager->createUser();
                        $user->setEmail($mail->fromAddress);
                        $user->setNome($mail->fromName);
                        $username = strtolower(str_replace(" ", "", $mail->fromName));
                        $user->setUsername($username);
                        $user->setPlainPassword($username."1");

                        $this->em->persist($user);

                    }
                    $articolo->setAutore($user);
                    $this->em->persist($articolo);

                }
            }
            $this->em->flush();


        }

    }
}