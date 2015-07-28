<?php
namespace AppBundle\Command;

use AppBundle\Entity\Attracco;
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

class ImportAttracchiCommand extends ContainerAwareCommand
{
    protected $output;
    protected $em;
    protected $connection;

    protected function configure()
    {
        $this
            ->setName('app:import-attracchi')
            ->setDescription('Importa attracchi');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->connection = $this->getContainer()->get('database_connection');

        $query = "select p.nome as 'nome_porto',u.mail,b.* from barche b,utenti u,porti p where p.ID=b.IDp and u.ID=IDu";

        $res = $this->connection->executeQuery($query)->fetchAll();

        $this->output->writeln("<comment>Importing ".count($res)." </comment>");

        foreach ($res as $data) {


            $attracco=new Attracco();
            $this->output->writeln("<comment>Importing: ".$data['ID']." </comment>");

            $attracco->setPorto($this->getContainer()->get('doctrine')
                ->getRepository('AppBundle:Porto')->findOneByNome($data['nome_porto']));

            $attracco->setUtente($this->getContainer()->get('doctrine')
                ->getRepository('AppBundle:User')->findOneByEmail($data['mail']));

            $this->em->persist($attracco);
            $this->em->flush();
        }


    }
}