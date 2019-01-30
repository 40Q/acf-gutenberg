<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CreateBlockCommand extends Command
{
    protected function configure()
    {
        $this->setName('create-block')
            ->setDescription('Create a Gutenberg Block')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Name of the block'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        exec('say ' . $input->getArgument('name'));
    }
}
