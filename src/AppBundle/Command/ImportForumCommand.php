<?php
namespace AppBundle\Command;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\DBAL\Connection;
use BlogBundle\Entity\Articolo;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use CCDNForum\ForumBundle\Entity\Post;
use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Board;

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

        $query = "select * from forum1 where stato=0 and idr=0";

        $res = $this->connection->executeQuery($query)->fetchAll();

        $this->output->writeln("<comment>Importing ".count($res)." </comment>");

        foreach ($res as $data) {
            $countPost = array();
            $countRisposte = array();
            $firstTopic = new Topic();
            $firstTopic->setTitle($data['titolo']);
            $firstTopic->setCachedViewCount(1);
            $firstTopic->setBoard(
                $this->getContainer()->get('doctrine')
                    ->getRepository('CCDNForumForumBundle:Board')->find($data['forum'])
            );

            $this->em->persist($firstTopic);
            $this->em->flush();

            $post = new Post();
            $post->setTopic($firstTopic);
            $dataPost = new \DateTime($data['data']);
            $ora = new \DateTime($data['ora']);
            $post->setCreatedDate(new \DateTime($dataPost->format("Y-m-d")." ".$ora->format("H:i:m")));
            $post->setCreatedBy(
                $this->getContainer()->get('doctrine')
                    ->getRepository('AppBundle:User')->findOneByUsername($data['autore'])
            );
            $post->setBody($data['testo']);

            $this->em->persist($post);
            $this->em->flush();
            $countPost[] = $post;
            $firstTopic->setFirstPost($post);
            $firstTopic->setLastPost($post);

            $this->em->merge($firstTopic);
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
                $post->setBody($risposta['testo']);

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
            }

        }


    }
}