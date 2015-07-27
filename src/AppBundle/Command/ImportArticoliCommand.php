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

        $query = "select * from comunicati";

        $res = $this->connection->executeQuery($query)->fetchAll();

        $this->output->writeln("<comment>Importing ".count($res)." </comment>");

        foreach ($res as $data) {

            $utente = $this->getContainer()->get('doctrine')
                ->getRepository('BlogBundle:Articolo')->findOneByIdOriginale($data['ID']);

            if ($utente) {
                $this->output->writeln("<comment>Gia presente: ".$data['titolo']." </comment>");
            } else {
                $this->output->writeln("<comment>Importing: ".$data['titolo']." </comment>");

                $utente = new Articolo();
                $utente->setIdOriginale($data['ID']);
                $utente->setTitolo($data['Titolo']);
                $utente->setTags(str_replace("$",", ",$data['ricerche']));
                $utente->setTesto(nl2br($data['testo'])."<br><br>Inviato da: ".$data['stampa']);
                $utente->setStato("ATTIVO");
                $utente->setTimestamp(new \DateTime($data['Data']));
                $utente->setAutore(
                    $this->getContainer()->get('doctrine')
                        ->getRepository('AppBundle:User')->findOneByUsername("ToroSeduto")
                );
                $categoria = $this->getContainer()->get('doctrine')
                    ->getRepository('BlogBundle:Categoria')->find(2);
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
                ->getRepository('BlogBundle:Articolo')->findOneByIdOriginale($data['titolo']);

            if ($utente) {
                $this->output->writeln("<comment>Gia presente: ".$data['titolo']." </comment>");
            } else {
                $this->output->writeln("<comment>Importing: ".$data['titolo']." </comment>");

                $utente = new Articolo();
                $utente->setIdOriginale($data['ID']);
                $utente->setTitolo($data['titolo']);
                $utente->setStato("ATTIVO");
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
                    0=>11,
                    1=>12,
                    2=>13,
                    3=>14,
                    4=>15
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