<?php
namespace Shove\CLI;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class CommandInit extends ShoveCLI
{
    protected $commandName = 'init';
    protected $commandDescription = "Generate new GB block with ACF";


    protected function configure()
    {
        $this->theme_blocks_dir = get_template_directory().'/acf-gutenberg/blocks/';
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
        ;
    }

    protected function command_init()
    {
		ShovePrint::info("Command: INIT!");

    }

}
