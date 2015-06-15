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

class ImportArticoliCommand extends ContainerAwareCommand
{
    protected $output;
    protected $em;
    protected $connection;

    protected function configure()
    {
        $this
            ->setName('app:import-articoli')
            ->setDescription('Importa articoli');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->connection = $this->getContainer()->get('database_connection');

        $query = "select * from articoli";

        $res = $this->connection->executeQuery($query)->fetchAll();

        $this->output->writeln("<comment>Importing ".count($res)." </comment>");

        foreach ($res as $data) {

            $utente = $this->getContainer()->get('doctrine')
                ->getRepository('BlogBundle:Articolo')->findOneById($data['id']);

            if ($utente) {
                $this->output->writeln("<comment>Gia presente: ".$data['titolo']." </comment>");
            } else {
                $this->output->writeln("<comment>Importing: ".$data['titolo']." </comment>");

                $utente = new Articolo();
                $utente->setId($data['id']);
                $utente->setTitolo($data['titolo']);
                $utente->setTesto($data['nomefile']);
                $utente->setAutore(
                    $this->getContainer()->get('doctrine')
                        ->getRepository('AppBundle:User')->findOneByUsername("ToroSeduto")
                );
                $categoria = $this->getContainer()->get('doctrine')
                    ->getRepository('BlogBundle:Categoria')->findOneByNome(ucfirst($data['categoria']));
                if ($categoria) {
                    $utente->setCategoria($categoria);
                    $this->em->persist($utente);
                    $this->em->flush();
                } else {

                }

            }

        }







        //IMPORT DELLE RICETTE


        $query = "select * from ricette";

        $res = $this->connection->executeQuery($query)->fetchAll();

        $this->output->writeln("<comment>Importing ".count($res)." </comment>");

        foreach ($res as $data) {

            $utente = $this->getContainer()->get('doctrine')
                ->getRepository('BlogBundle:Articolo')->findOneByTitolo($data['titolo']);

            if ($utente) {
                $this->output->writeln("<comment>Gia presente: ".$data['titolo']." </comment>");
            } else {
                $this->output->writeln("<comment>Importing: ".$data['titolo']." </comment>");

                $utente = new Articolo();

                $utente->setTitolo($data['titolo']);


                $testo="Tempo: ".$data['tempo'];
                $testo.="<br><br>Persone: ".$data['persone'];
                $testo.="<br><br>".$data['ingredienti'];
                $testo.="<br><br>".$data['ricetta'];

                $utente->setTesto($testo);
                $utente->setAutore(
                    $this->getContainer()->get('doctrine')
                        ->getRepository('AppBundle:User')->findOneByUsername($data['Autore'])
                );


                $mappaGeneri=array(
                    0=>20,
                    1=>21,
                    2=>22,
                    3=>23,
                    4=>24
                );

                $categoria = $this->getContainer()->get('doctrine')
                    ->getRepository('BlogBundle:Categoria')->find($mappaGeneri[$data['genere']]);



                if ($categoria) {
                    $utente->setCategoria($categoria);
                    $this->em->persist($utente);
                    $this->em->flush();
                } else {

                }

            }

        }


    }
}