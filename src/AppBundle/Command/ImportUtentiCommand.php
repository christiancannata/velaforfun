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
use PhpImap\Mailbox as MailBox;

class ImportUtentiCommand extends ContainerAwareCommand
{
    protected $output;
    protected $em;
    protected $connection;

    protected function configure()
    {
        $this
            ->setName('app:import-utenti')
            ->setDescription('Importa utenti');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->connection = $this->getContainer()->get('database_connection');

        $query = "select * from utenti";

        $res = $this->connection->executeQuery($query)->fetchAll();

        $this->output->writeln("<comment>Importing ".count($res)." </comment>");

        foreach ($res as $data) {

            $utente = $this->getContainer()->get('doctrine')
                ->getRepository('AppBundle:User')->findOneByUsername($data['username']);

            if($utente){
                $this->output->writeln("<comment>Gia presente: ".$data['username']." </comment>");
            }else{
                $this->output->writeln("<comment>Importing: ".$data['username']." </comment>");

                $utente=new User();
                $utente->setIdOriginale($data['ID']);
                $utente->setNome("");
                $utente->setEmail($data['mail']);
                $utente->setTimestamp(new \DateTime($data['data']));
                $utente->setCognome("");
                $utente->setFirma($data['firma']);
                $utente->setUsername($data['username']);
                $utente->setPlainPassword($data['password']);
                $utente->setPrivacy($data['privacy']);
                $utente->setProfilePicturePath($data['avart']);
                $utente->setEnabled(1);

                $this->em->persist($utente);
                $this->em->flush();
            }

        }


    }
}