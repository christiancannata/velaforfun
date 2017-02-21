<?php
namespace AppBundle\Command;

use AppBundle\Entity\CategoriaVideo;
use AppBundle\Entity\Foto;
use AppBundle\Entity\GalleriaFoto;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\DBAL\Connection;
use AppBundle\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use PhpImap\Mailbox as MailBox;

class ImportFotoCommand extends ContainerAwareCommand
{
    protected $output;
    protected $em;
    protected $connection;

    protected function configure()
    {
        $this
            ->setName('app:import-foto')
            ->setDescription('Importa foto');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $foto = $this->getContainer()->get("doctrine")->getManager()->getRepository("\\AppBundle\\Entity\\Foto")->findBy(["tag" => null]);


        foreach ($foto as $obj) {
            $galleria = $obj->getGalleria();

            if (!$galleria)
                continue;

            $obj->setTag(json_encode([str_replace([" ", "-"], "_", strtoupper($galleria->getNome()))]));
            $this->getContainer()->get("doctrine")->getManager()->merge($obj);
        }


        $foto = $this->getContainer()->get("doctrine")->getManager()->getRepository("\\AppBundle\\Entity\\Foto")->findBy(["tag" => ""]);


        foreach ($foto as $obj) {
            $galleria = $obj->getGalleria();

            if (!$galleria)
                continue;

            $obj->setTag(json_encode([str_replace([" ", "-"], "_", strtoupper($galleria->getNome()))]));
            $this->getContainer()->get("doctrine")->getManager()->merge($obj);
        }
        $this->getContainer()->get("doctrine")->getManager()->flush();

    }
}