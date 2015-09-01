<?php
namespace AppBundle\Command;

use AppBundle\Entity\CategoriaVideo;
use AppBundle\Entity\Foto;
use AppBundle\Entity\GalleriaFoto;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\DBAL\Connection;
use AppBundle\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use PhpImap\Mailbox as MailBox;

class ImportFotoCommand extends ContainerAwareCommand
{
    protected $output;
    protected $em;
    protected $connection;

    protected function configure()
    {
        $this
            ->setName('app:import-foto')
            ->setDescription('Importa foto');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->connection = $this->getContainer()->get('database_connection');

        $query = "select * from img";

        $res = $this->connection->executeQuery($query)->fetchAll();

        $this->output->writeln("<comment>Importing ".count($res)." </comment>");

        foreach ($res as $data) {
            if($data['categoria']!=null){
                $categoria = $this->getContainer()->get('doctrine')
                    ->getRepository('AppBundle:GalleriaFoto')->findOneByNome(ucfirst($data['categoria']));
                if(!$categoria){
                    $categoria=new GalleriaFoto();
                    $categoria->setNome(ucfirst($data['categoria']));
                    $this->em->persist($categoria);
                    $this->em->flush();
                }
                if ($categoria) {

                    $video = $this->getContainer()->get('doctrine')
                        ->getRepository('AppBundle:Foto')->findOneByNome(ucfirst($data['Titolo']));
                    if(!$video){
                        $video = new Foto();
                    }
                    $video->setInEvidenza(true);
                    $video->setNome($data['Titolo']);
                    $video->setAutore($data['autore']);
                    $video->setImmagine($data['nomefile']);
                    $video->setGalleria(
                        $categoria
                    );

                    $this->em->persist($video);

                }
            }



        }
        $this->em->flush();

    }
}