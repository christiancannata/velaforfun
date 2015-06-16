<?php
namespace AppBundle\Command;

use AppBundle\Entity\CategoriaVideo;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\DBAL\Connection;
use AppBundle\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use PhpImap\Mailbox as MailBox;

class ImportVideoCommand extends ContainerAwareCommand
{
    protected $output;
    protected $em;
    protected $connection;

    protected function configure()
    {
        $this
            ->setName('app:import-video')
            ->setDescription('Importa utenti');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->connection = $this->getContainer()->get('database_connection');

        $query = "select * from video2";

        $res = $this->connection->executeQuery($query)->fetchAll();

        $this->output->writeln("<comment>Importing ".count($res)." </comment>");

        foreach ($res as $data) {
            if($data['Categoria']!=null){
                $categoria = $this->getContainer()->get('doctrine')
                    ->getRepository('AppBundle:CategoriaVideo')->find($data['Categoria']);
                if ($categoria) {
                    $video = new Video();
                    $video->setNome($data['titolo']);
                    $video->setDescrizione($data['commento']);
                    $video->setLink($data['video2']);
                    $video->setInEvidenza(1);
                    $video->setCategoria(
                        $categoria
                    );

                    $this->em->persist($video);

                }
            }



        }
        $this->em->flush();

    }
}