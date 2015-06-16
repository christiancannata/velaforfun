<?php
namespace AppBundle\Command;

use AppBundle\Entity\Attracco;
use AppBundle\Entity\CommentoPorto;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\DBAL\Connection;
use BlogBundle\Entity\Articolo;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use PhpImap\Mailbox as MailBox;

class ImportCommentiPortoCommand extends ContainerAwareCommand
{
    protected $output;
    protected $em;
    protected $connection;

    protected function configure()
    {
        $this
            ->setName('app:import-commenti-porto')
            ->setDescription('Importa commenti porto');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->connection = $this->getContainer()->get('database_connection');

        $query = "select * from commenti_porti";

        $res = $this->connection->executeQuery($query)->fetchAll();

        $this->output->writeln("<comment>Importing ".count($res)." </comment>");

        foreach ($res as $data) {


            $attracco=new CommentoPorto();
            $this->output->writeln("<comment>Importing: ".$data['ID']." </comment>");
            $attracco->setTimestamp(new \DateTime($data['tempo']));
            $attracco->setTesto($data['commento']);
            if($data['voto']==-1){
                $attracco->setTipoCommento("NEGATIVO");
            }
            if($data['voto']==0){
                $attracco->setTipoCommento("NEUTRO");
            }
            if($data['voto']==1){
                $attracco->setTipoCommento("POSITIVO");
            }
            $attracco->setPorto($this->getContainer()->get('doctrine')
                ->getRepository('AppBundle:Porto')->findOneByIdOriginale($data['ID_porto']));

            $attracco->setUtente($this->getContainer()->get('doctrine')
                ->getRepository('AppBundle:User')->findOneByIdOriginale($data['ID_ut']));

            $this->em->persist($attracco);
            $this->em->flush();
        }


    }
}