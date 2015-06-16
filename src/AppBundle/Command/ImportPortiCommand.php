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
use AppBundle\Entity\Porto;

class ImportPortiCommand extends ContainerAwareCommand
{
    protected $output;
    protected $em;
    protected $connection;

    protected function configure()
    {
        $this
            ->setName('app:import-porti')
            ->setDescription('Importa porti');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->connection = $this->getContainer()->get('database_connection');

        $query = "select * from porti";

        $res = $this->connection->executeQuery($query)->fetchAll();

        $this->output->writeln("<comment>Importing ".count($res)." </comment>");

        foreach ($res as $data) {

            $utente = $this->getContainer()->get('doctrine')
                ->getRepository('AppBundle:Porto')->findOneByIdOriginale($data['ID']);

            if ($utente) {
                $this->output->writeln("<comment>Gia presente: ".$data['Nome']." </comment>");
            } else {
                $attracco = new Porto();
                $this->output->writeln("<comment>Importing: ".$data['Nome']." </comment>");

                $attracco->setRegione($data['Regione']);
                $attracco->setNome($data['Nome']);
                $attracco->setLatitudine($data['lat']);
                $attracco->setLongitudine($data['long']);
                $attracco->setDatiOver($data['datiover']);
                $attracco->setPostiTotale($data['posti_totale']);
                $attracco->setPostiTransito($data['posti_transito']);
                $attracco->setEmail(array_map('trim', explode(";", $data['mail'])));
                $attracco->setIdOriginale($data['ID']);

                $this->em->persist($attracco);
                $this->em->flush();
                $this->output->writeln("<comment>Imported: ".$data['Nome']." </comment>");
            }
        }


    }
}