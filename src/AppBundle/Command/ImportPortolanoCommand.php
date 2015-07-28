<?php
namespace AppBundle\Command;

use AppBundle\Entity\Attracco;
use AppBundle\Entity\SegnalazionePortolano;
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

class ImportPortolanoCommand extends ContainerAwareCommand
{
    protected $output;
    protected $em;
    protected $connection;

    protected function configure()
    {
        $this
            ->setName('app:import-portolano')
            ->setDescription('Importa portolano');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->connection = $this->getContainer()->get('database_connection');

        $query = "select u.mail,b.* from segnalazioni b,utenti u where u.ID=ID_ut";

        $res = $this->connection->executeQuery($query)->fetchAll();

        $this->output->writeln("<comment>Importing ".count($res)." </comment>");

        foreach ($res as $data) {


            $attracco=new SegnalazionePortolano();
            $this->output->writeln("<comment>Importing: ".$data['ID']." </comment>");



            $attracco->setUtente($this->getContainer()->get('doctrine')
                ->getRepository('AppBundle:User')->findOneByEmail($data['mail']));


            $attracco->setLatitudine($data['latitudine']);
            $attracco->setLongitudine($data['longitudine']);
            $attracco->setTimestamp(new \DateTime($data['tempo']));
            $attracco->setDescrizione($data['commento']);

            $this->em->persist($attracco);
            $this->em->flush();
        }


    }
}