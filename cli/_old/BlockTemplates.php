<?php
namespace ACFB\CLI;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BlockTemplates extends AcfgbCommand
{
    protected $commandName = 'template';
    protected $commandDescription = "Get templates blocks list";

    protected $commandArgumentAction = "action";
    protected $commandArgumentActionDescription = "action to execute";

    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->addArgument(
                $this->commandArgumentAction,
                InputArgument::REQUIRED,
                $this->commandArgumentActionDescription
            )
        ;
    }

    protected function command_init()
    {
        if ($this->input->getArgument($this->commandArgumentAction)){
            $action = $this->input->getArgument($this->commandArgumentAction);
            switch ($action){
                case 'list':
                    $this->the_blocks_list();
                    break;
                default:
                    $this->print('No valid action',
                        'comment');
                    break;
            }
        }
    }
}
