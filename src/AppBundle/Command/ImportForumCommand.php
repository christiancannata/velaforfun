<?php
namespace AppBundle\Command;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\DBAL\Connection;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use CCDNForum\ForumBundle\Entity\Post;
use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Board;
use CCDNForum\ForumBundle\Entity\Subscription;
use AppBundle\Entity\CompatibilitaForum;

class ImportForumCommand extends ContainerAwareCommand
{
    protected $output;
    protected $em;
    protected $connection;

    protected function configure()
    {
        $this
            ->setName('app:import-forum')
            ->setDescription('Importa forum');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->connection = $this->getContainer()->get('database_connection');

        $query = "select f.* from forum1 f where f.stato=0 and f.idr=0 and ID not in (select distinct id_old from compatibilita_forum) order by ID";

        $res = $this->connection->executeQuery($query)->fetchAll();

        $this->output->writeln("<comment>Importing ".count($res)." </comment>");


        foreach ($res as $data) {

            $inserito = $this->getContainer()->get('doctrine')
                ->getRepository('AppBundle:CompatibilitaForum')->findOneByIdOld($data['ID']);
            if (!$inserito && $data['titolo'] != "") {
                $this->output->writeln("<comment>Importing: ".$data['titolo']." </comment>");
                $user = $this->getContainer()->get('doctrine')
                    ->getRepository('AppBundle:User')->findOneBy(array("username"=>$data['autore']));

                if (!$user) {

                    $queryU = "select * from utenti where username=".$data['autore'];

                    $utenti = $this->connection->executeQuery($queryU)->fetchAll();
                    $data=$utenti[0];

                    $this->output->writeln("<comment>Importing: ".$data['username']." </comment>");

                    $utente = new User();
                    $utente->setIdOriginale($data['ID']);
                    $utente->setNome("");
                    $utente->setEmail($data['mail']);
                    $utente->setTimestamp(new \DateTime($data['data']));
                    $utente->setCognome("");

                    $data['firma'] = str_replace("[B]", "<strong>", $data['firma']);
                    $data['firma'] = str_replace("[/B]", "</strong>", $data['firma']);
                    $data['firma'] = str_replace("[I]", "<i>", $data['firma']);
                    $data['firma'] = str_replace("[/I]", "</i>", $data['firma']);


                    $utente->setFirma($data['firma']);
                    $utente->setUsername($data['username']);
                    $utente->setPlainPassword($data['password']);
                    $utente->setPrivacy($data['privacy']);
                    $utente->setProfilePicturePath($data['avart']);
                    $utente->setEnabled(1);

                    $this->em->persist($utente);


                    $repository = $this->getContainer()->get('doctrine')
                        ->getRepository('AppBundle\Entity\Newsletter\Subscriber');
                    $iscritto = $repository->findOneByEmail($data['mail']);


                    if (!$iscritto) {
                        $repository = $this->getContainer()->get('doctrine')
                            ->getRepository('AppBundle\Entity\Newsletter\Mandant');
                        $mandant = $repository->find(1);

                        $iscrizione = new \AppBundle\Entity\Newsletter\Subscriber();
                        $iscrizione->setMandant($mandant);
                        $iscrizione->setLocale("it");
                        $iscrizione->setEmail($data['mail']);
                        $iscrizione->setFirstName($data['username']);
                        $iscrizione->setLastName("");
                        $iscrizione->setGender("MALE");
                        $iscrizione->setCompanyname("");
                        $iscrizione->setTitle("");
                        $iscrizione->addGroup(
                            $this->getContainer()->get('doctrine')
                                ->getRepository('AppBundle\Entity\Newsletter\Group')->find(1)
                        );


                        $em = $this->getContainer()->get('doctrine')->getManager();

                        $em->persist($iscrizione);

                    }


                    $this->em->flush();
                    $user = $iscrizione;
                }


                if ($user) {

                    $redirect = new CompatibilitaForum();
                    $redirect->setIdOld($data['ID']);

                    $countPost = array();
                    $countRisposte = array();
                    $firstTopic = new Topic();
                    $firstTopic->setTitle($data['titolo']);
                    $firstTopic->setCachedViewCount($data['visite']);
                    $firstTopic->setBoard(
                        $this->getContainer()->get('doctrine')
                            ->getRepository('CCDNForumForumBundle:Board')->find($data['forum'])
                    );

                    $this->em->persist($firstTopic);
                    $this->em->flush();
                    $countPost[] = $firstTopic;
                    $redirect->setIdNew($firstTopic->getId());

                    $this->em->persist($redirect);

                    $post = new Post();
                    $post->setTopic($firstTopic);
                    $dataPost = new \DateTime($data['data']);
                    $ora = new \DateTime($data['ora']);
                    $post->setCreatedDate(new \DateTime($dataPost->format("Y-m-d")." ".$ora->format("H:i:m")));
                    $post->setCreatedBy(
                        $user
                    );


                    $data['testo'] = str_replace("[B]", "<strong>", $data['testo']);
                    $data['testo'] = str_replace("[/B]", "</strong>", $data['testo']);
                    $data['testo'] = str_replace("[I]", "<i>", $data['testo']);

                    $data['testo'] = str_replace("[/I]", "</i>", $data['testo']);
                    $data['testo'] = str_replace("[SIZE=1]", "<i>", $data['testo']);
                    $data['testo'] = str_replace("[/SIZE]", "</i>", $data['testo']);
                    $data['testo'] = str_replace("[SIZE=2]", "<i>", $data['testo']);
                    $data['testo'] = str_replace("[SIZE=3]", "<i>", $data['testo']);

                    $data['testo'] = str_replace("[/COLOR]", "</font>", $data['testo']);
                    $data['testo'] = str_replace("[COLOR=green]", "<font style='color:green'>", $data['testo']);
                    $data['testo'] = str_replace("[COLOR=red]", "<font style='color:red'>", $data['testo']);
                    $data['testo'] = str_replace("[COLOR=blue]", "<font style='color:blue'>", $data['testo']);

                    $post->setBody(nl2br($data['testo']));

                    $this->em->persist($post);
                    $this->em->flush();
                    $countPost[] = $post;
                    $firstTopic->setFirstPost($post);
                    $firstTopic->setLastPost($post);

                    $this->em->merge($firstTopic);


                    $subscription = new Subscription();
                    $subscription->setTopic($firstTopic);
                    $subscription->setOwnedBy(
                        $this->getContainer()->get('doctrine')
                            ->getRepository('AppBundle:User')->findOneByUsername($data['autore'])
                    );
                    $subscription->setSubscribed(true);
                    $subscription->setRead(false);

                    $forum = $this->getContainer()->get('doctrine')
                        ->getRepository('CCDNForumForumBundle:Forum')->find(1);

                    $subscription->setForum($forum);

                    $this->em->persist($subscription);


                    $this->em->flush();

                    $query = "select * from forum1 where stato=0 and idr=".$data['ID']." ";

                    $risposte = $this->connection->executeQuery($query)->fetchAll();

                    foreach ($risposte as $risposta) {

                        $post = new Post();
                        $post->setTopic($firstTopic);
                        $dataPost = new \DateTime($data['data']);
                        $ora = new \DateTime($data['ora']);
                        $post->setCreatedDate(new \DateTime($dataPost->format("Y-m-d")." ".$ora->format("H:i:m")));
                        $post->setCreatedBy(
                            $this->getContainer()->get('doctrine')
                                ->getRepository('AppBundle:User')->findOneByUsername($risposta['autore'])
                        );

                        $subscription = new Subscription();
                        $subscription->setTopic($firstTopic);
                        $subscription->setOwnedBy(
                            $this->getContainer()->get('doctrine')
                                ->getRepository('AppBundle:User')->findOneByUsername($risposta['autore'])
                        );
                        $subscription->setSubscribed(true);
                        $subscription->setRead(true);
                        $this->em->persist($subscription);

                        $risposta['testo'] = str_replace("[B]", "<strong>", $risposta['testo']);
                        $risposta['testo'] = str_replace("[/B]", "</strong>", $risposta['testo']);
                        $risposta['testo'] = str_replace("[I]", "<i>", $risposta['testo']);
                        $risposta['testo'] = str_replace("[/I]", "</i>", $risposta['testo']);
                        $risposta['testo'] = str_replace("[SIZE=1]", "<i>", $risposta['testo']);
                        $risposta['testo'] = str_replace("[/SIZE]", "</i>", $risposta['testo']);
                        $risposta['testo'] = str_replace("[SIZE=2]", "<i>", $risposta['testo']);
                        $risposta['testo'] = str_replace("[SIZE=3]", "<i>", $risposta['testo']);

                        $risposta['testo'] = str_replace("[/COLOR]", "</font>", $risposta['testo']);
                        $risposta['testo'] = str_replace(
                            "[COLOR=green]",
                            "<font style='color:green'>",
                            $risposta['testo']
                        );
                        $risposta['testo'] = str_replace("[COLOR=red]", "<font style='color:red'>", $risposta['testo']);
                        $risposta['testo'] = str_replace(
                            "[COLOR=blue]",
                            "<font style='color:blue'>",
                            $risposta['testo']
                        );

                        $post->setBody(nl2br($risposta['testo']));

                        $this->em->persist($post);
                        $this->em->flush();
                        $countRisposte[] = $post;

                        $firstTopic->setLastPost($post);


                        $this->em->merge($firstTopic);


                        $this->em->flush();
                    }


                    if ($firstTopic) {
                        $firstTopic->setCachedReplyCount(count($countRisposte));
                        $this->em->merge($firstTopic);
                        $this->em->flush();
                    }


                    $board = $firstTopic->getBoard();
                    if ($board) {
                        $board->setLastPost(end($countPost));
                        $board->setCachedTopicCount(
                            count(
                                $this->getContainer()->get('doctrine')
                                    ->getRepository('CCDNForumForumBundle:Topic')->findByBoard($board)
                            )
                        );
                        $board->setCachedPostCount(count($countPost));
                        $this->em->merge($board);
                        $this->em->flush();

                        $this->output->writeln("<info>Importing: ".$data['titolo']." </info>");
                    }


                }


            }


        }


    }

    private function get_string_between($string, $start, $end)
    {
        $string = " ".$string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return "";
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;

        return substr($string, $ini, $len);
    }

}