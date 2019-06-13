<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class BlockSample extends AcfgbCommand
{
    protected $commandName = 'sample';
    protected $commandDescription = "Import sample block";


    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
        ;
    }

    protected function command_init()
    {
        $command = $this->getApplication()->find('import');

        $arguments = [
            'command' => 'import',
            'block'    => 'sample-block',
            'prefix'    => 'acfgb',
        ];

        $importInput = new ArrayInput($arguments);
        $returnCode = $command->run($importInput, $this->output);
    }


}
