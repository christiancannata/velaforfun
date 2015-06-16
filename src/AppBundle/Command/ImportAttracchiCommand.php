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

        $query = "select * from barche";

        $res = $this->connection->executeQuery($query)->fetchAll();

        $this->output->writeln("<comment>Importing ".count($res)." </comment>");

        foreach ($res as $data) {


            $attracco=new Attracco();
            $this->output->writeln("<comment>Importing: ".$data['ID']." </comment>");

            $attracco->setPorto($this->getContainer()->get('doctrine')
                ->getRepository('AppBundle:Porto')->find($data['IDp']));

            $attracco->setUtente($this->getContainer()->get('doctrine')
                ->getRepository('AppBundle:User')->findOneByIdOriginale($data['IDu']));

            $this->em->persist($attracco);
            $this->em->flush();
        }


    }
}