<?php
namespace AppBundle\Command;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\DBAL\Connection;
use BlogBundle\Entity\Articolo;
use AppBundle\Entity\User;
use AppBundle\Entity\Foto;
use AppBundle\Entity\GalleriaFoto;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use PhpImap\Mailbox as MailBox;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;



class CheckEmailCommand extends ContainerAwareCommand
{
    protected $output;
    protected $em;
    protected $connection;

    protected function configure()
    {
        $this
            ->setName('app:check-email')
            ->addOption(
                'limit',
                null,
                InputOption::VALUE_OPTIONAL,
                'Numero di comunicati da scaricare'
            )
            ->addOption(
                'dryrun',
                null,
                InputOption::VALUE_OPTIONAL,
                'Numero di comunicati da scaricare'
            )
            ->setDescription('Controlla email comunicati');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $mailbox = new Mailbox(
            '{imap.gmail.com:993/imap/ssl}INBOX',
            'comunicati@velaforfun.com',
            'comunicati01 ',
            '/tmp'
        );
        $mails = array();

        $fs = new Filesystem();


        $mailsIds = $mailbox->searchMailBox('UNSEEN');
        $repositoryArticolo = $this->getContainer()->get('doctrine')
            ->getRepository('BlogBundle:Articolo');
        if (!$mailsIds) {
            die('Mailbox is empty');
        } else {

            //  $mailsIds = $mailbox->sortMails();
            $output->writeln('<info> - Trovate email da leggere:'.count($mailsIds).'</info>');


            if($input->getOption('limit')){
                $mailsIds = array_slice($mailsIds, 0, $input->getOption('limit'));
            }
            $comunicati = array();


            if(!$input->getOption('dryrun')){


                foreach ($mailsIds as $mailId) {
                    $ora=new \DateTime();
                    $output->writeln('<info>'.$ora->format("d-m-Y H:i").' - Leggo email ID:'.$mailId.'</info>');
                    $mail = $mailbox->getMail($mailId);

                    $emailComunicato = $repositoryArticolo->findOneBy(array("idComunicato" => $mail->id));
                    if (!$emailComunicato) {

                        $articolo = new Articolo();
                        $output->writeln('<comment>'.$mail->subject.' from '.$mail->fromAddress.' </comment>');
                        $articolo->setIdComunicato($mail->id);
                        $articolo->setTitolo(strtoupper($mail->subject));
                        $articolo->setStato("BOZZA");
                        $articolo->setTesto(str_replace(array('href="%5C%22http','href="\&quot;','\&quot;">','\"',"\'"),array('href="http','href="http','">','""',"'"),addslashes($mail->textHtml)));
                        $articolo->generatePermalink($mail->subject);
                        $articolo->setCategoria(
                            $this->getContainer()->get('doctrine')
                                ->getRepository('BlogBundle:Categoria')->find(2)
                        );
                        $repository = $this->getContainer()->get('doctrine')
                            ->getRepository('AppBundle:User');
                        $user = $repository->findOneBy(array("email" => $mail->fromAddress));
                        if (!$user) {

                            $username = strtolower(str_replace(" ", "", $mail->fromName));

                            $user2 = $repository->findOneBy(array("username" => $username));

                            if (!$user2) {

                                $userManager = $this->getContainer()->get('fos_user.user_manager');
                                $user = $userManager->createUser();
                                $user->setEmail($mail->fromAddress);
                                $user->setNome($mail->fromName);
                                $user->setUsername($username);
                                $user->setPlainPassword($username."1");
                                $this->em->persist($user);
                                $this->em->flush();
                            } else {
                                $user = $user2;
                            }


                        }
                        $articolo->setAutore($user);
                        $allegati = $mail->getAttachments();
                        $allegatiValidi=[];
                        $immagini=[];
                        foreach ($allegati as $allegato) {
                            $path_parts = pathinfo($allegato->filePath);
                            if ($path_parts['extension'] == "doc" || $path_parts['extension'] == "pdf" || $path_parts['extension'] == "txt" || $path_parts['extension'] == "docx") {
                                $allegatiValidi[]=$allegato;
                            }else{
                                $immagini[]=$allegato;
                            }


                        }



                        if(!empty($immagini)){
                            $gallery = new GalleriaFoto();


                            $gallery->setNome($mail->subject);
                            $gallery->setDescrizione("");
                            $gallery->setInGallery(false);
                            $this->em->persist($gallery);
                            $this->em->flush();
                            if(isset($immagini[0])){
                                $fs = new Filesystem();
                                $path_parts = pathinfo($immagini[0]->filePath);
                                $fs->copy($immagini[0]->filePath, '/var/www/web/images/articoli/'.$path_parts['filename'].".".$path_parts['extension']);

                                $articolo->setImmagine($path_parts['filename'].".".$path_parts['extension']);
                            }

                            $this->creaGalleria($gallery,$immagini);

                            $articolo->setGallery($gallery);

                        }


                        $output->writeln('<info>Trovati allegati validi:'.count($allegatiValidi).'</info>');

                        $allegatiValidi=array_slice($allegatiValidi,0,4);
                        foreach ($allegatiValidi as $key=>$allegato) {
                            try {
                                $path_parts = pathinfo($allegato->filePath);
                                $numeroAllegato = "setAllegato".($key+1);
                                $output->writeln('<info>setto allegato:'.$numeroAllegato.' - '.$allegato->filePath.'</info>');

                                $fs->copy($allegato->filePath, '/var/www/web/uploads/allegati-articoli/'.substr($path_parts['filename'],0,30).".".$path_parts['extension']);
                                $articolo->$numeroAllegato(substr($path_parts['filename'],0,30).".".$path_parts['extension']);

                            } catch (IOException $e) {
                                echo "Errore durante la copia del file";
                            }


                        }

                        $this->em->persist($articolo);
                        $ora=new \DateTime();
                        $comunicati[] = $articolo;
                        $this->em->flush();
                        $mailbox->markMailAsRead($mailId);
                        $output->writeln('<info>'.$ora->format("d-m-Y H:i").' - Email ID:'.$mailId.' importata!</info>');
                    }
            }

            }else{

            }


            if (count($comunicati) > 0) {

                //Create the Transport
                $transport = \Swift_MailTransport::newInstance();

//Create the Mailer using your created Transport
                $mailer = \Swift_Mailer::newInstance($transport);

                $messaggio = \Swift_Message::newInstance()
                    ->setSubject('Ci sono '.count($comunicati).' nuovi comunicati')
                    ->setFrom('info@velaforfun.com')
                    ->setTo('velaforfun@velaforfun.com')
                    ->setBcc('christian1488@hotmail.it')
                    ->setBody(
                        $this->getContainer()->get('templating')->render(
                        // app/Resources/views/Emails/registrazione.html.twig
                            'Emails/comunicati.html.twig',
                            array('comunicati' => $comunicati)
                        ),
                        'text/html'
                    );
                $mailer->send($messaggio);

            }


        }


        $process = new Process('rm -rf /tmp/*');

        try {
            $process->mustRun();

            echo $process->getOutput();
        } catch (ProcessFailedException $e) {
            echo $e->getMessage();
        }



    }

    private function creaGalleria($gallery,$files){

        $foto = array();

        $fs = new Filesystem();
        $this->output->writeln('<info>Trovate immagini valide:'.count($files).'</info>');
        // $file will be an instance of Symfony\Component\HttpFoundation\File\UploadedFile
        foreach ($files as $uploadedFile) {

            try {
                $path_parts = pathinfo($uploadedFile->filePath);
                $this->output->writeln('<info>Copio:'.$path_parts['filename'].'</info>');

                $fs->copy($uploadedFile->filePath, '/var/www/web/uploads/galleria_foto/'.$path_parts['filename'].".".$path_parts['extension']);



                $fileUpload = new Foto();
                $fileUpload->setImmagine($path_parts['filename'].".".$path_parts['extension']);
                $fileUpload->setNome($path_parts['filename']);
                $fileUpload->setGalleria($gallery);
                $fileUpload->setInEvidenza(true);
                $this->em->persist($fileUpload);

                $foto[] = $fileUpload;
            } catch (IOException $e) {
                echo "Errore durante la copia del file";
            }



        }

        $this->em->flush();
    }
}