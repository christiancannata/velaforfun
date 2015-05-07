<?php
namespace AppBundle\Command;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckEmailCommand extends ContainerAwareCommand
{
    protected $output;
    protected $em;
    protected $connection;

    protected function configure()
    {
        $this
            ->setName('app:check-email')
            ->setDescription('Controlla email comunicati')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $max = 1000;
        $offset = 0;

        $this->output = $output;
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->connection = $this->em->getConnection();
        $this->connection->exec('SET session wait_timeout = 5000');

        $compagnia = $this->em->getReference('FacileWsBunnyBundle:Compagnia', $input->getOption('compagnia'));
        $taskTypes = $input->getOption('task-type');
        $repository = $this->em->getRepository('FacileWsBunnyBundle:Task');

        $backofficeManager = $this->getContainer()->get('bunny.backoffice.manager');

        do {
            /** @var QueryBuilder $query */
            $queryBuilder = $this->connection->createQueryBuilder();
            $query = $queryBuilder
                ->select('t.id')
                ->from('task', 't')
                ->innerJoin('t', 'contratto', 'c', 't.id_contratto = c.id')
                ->innerJoin('c', 'offerta', 'o', 'c.id_offerta = o.id')
                ->where('t.id_user IS NULL')
                ->andWhere('o.id_compagnia = :compagnia')->setParameter('compagnia', $compagnia->getId());
            if (!empty($taskTypes)) {
                $query->andWhere("t.type IN (:types)")->setParameter('types', $taskTypes, Connection::PARAM_STR_ARRAY);
            }

            $query->setMaxResults($max)
                ->setFirstResult($offset);
            $result = $query->execute()->fetchAll();

            if ($result) {
                foreach ($result as $record) {
                    $this->output->writeln('Assigning task: ' . $record->getId());
                    $backofficeManager->assignTask($record);
                }
            } else {
                $output->writeln("<error>No more tasks found...</error>");
                return;
            }

            $output->writeln("<comment>About to flush them...</comment>");

            $this->em->flush();

            $offset += 1000;
        } while (!empty($result));
    }
}