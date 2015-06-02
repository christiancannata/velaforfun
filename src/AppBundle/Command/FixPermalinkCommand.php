<?php
namespace AppBundle\Command;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class FixPermalinkCommand extends ContainerAwareCommand
{
    protected $output;
    protected $em;
    protected $connection;

    protected function configure()
    {
        $this
            ->setName('app:fix-permalink')
            ->setDescription('mette i permalink');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->output = $output;


        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $porti = $this->getContainer()->get('doctrine')
            ->getRepository('AppBundle:Porto')->findAll();


        foreach ($porti as $porto) {

            $porto->generatePermalink();
            $this->em->merge($porto);
            $this->em->flush();

        }


    }
}