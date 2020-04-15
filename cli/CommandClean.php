<?php
namespace Shove\CLI;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class CommandClean extends ShoveCLI
{
    protected $commandName = 'clean';
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
		$helper = $this->getHelper('question');
		$question = new ConfirmationQuestion('<comment>!! Are you sure you want to delete all files?? (y/n) </comment>', false);
		$confirm = $helper->ask($this->input(), $this->output(), $question);

		if ($confirm == 'y' || $confirm == "yes"){
			ShovePrint::info("✓ ACF Gutenberg cleaned");

//				ShovePrint::info("------ Init ACFGB Clean ------");
//
//				// Delete block dir in theme
//				$this->delete_block_dir_in_theme();
//
//				// Delete blocks scss file in theme
//				$this->delete_block_scss_file();
//mk,
//				// Delete block cli file in theme
//				$this->delete_block_cli_file();
//
//				//$this->delete_blocks_scss_in_main();
//				ShovePrint::error(" - IMPORTANT! Remember delete block scss file reference in main.scss");
//				ShovePrint::error(" - IMPORTANT! If you are using custom JS, remember delete JS routes in main.js");

			ShovePrint::info($this->get_message('tasks_ready'));
		}else{
			ShovePrint::print("<comment>Action canceled</comment>. <info>Your files are safe =)</info>", 'comment');
		}

    }

}
