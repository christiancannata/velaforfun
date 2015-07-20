<?php
namespace Task;

use Mage\Task\AbstractTask;

class SharedFolderTask extends AbstractTask
{
    public function getName()
    {
        return 'Creating symbolic links...';
    }

    public function run()
    {
        $sharedFiles = $this->getParameter('sharedFiles', false);
        if ($sharedFiles) {
            $folder = $this->getConfig()->deployment('to');
            foreach (explode(",", $sharedFiles) as $sf) {
                $command = "ln -s $folder/shared/$sf ./$sf";
                $this->runCommandRemote($command);
            }
        }

        return true;
    }
}