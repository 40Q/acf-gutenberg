<?php
namespace Shove\CLI;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class CommandModules extends ShoveCLI
{
    protected $commandName = 'module';
    protected $commandDescription = "Generate new GB block with ACF";

	protected $action = "action";
	protected $actionDescription = "Action for block command";

    protected $newBlock = "name";
    protected $newBlockDescription = "Name for new block";

    protected $optionJs = "js"; // should be specified like "create {BlockName} --js"
    protected $optionJsDescription = 'If is set, create js route file for the block.';

    protected $optionTarget = "target"; // should be specified like "create {BlockName} --target=theme | --target=plugin"
    protected $optionTargetDescription = 'Select target to import block. Can be: theme or plugin';

    protected $optionTheme = "theme";
    protected $optionThemeDescription = 'Select theme to import block.';


    protected function configure()
    {
        $this->theme_blocks_dir = get_template_directory().'/acf-gutenberg/blocks/';
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
			->addArgument(
				$this->action,
				InputArgument::REQUIRED,
				$this->actionDescription
			)
            ->addArgument(
                $this->newBlock,
                InputArgument::OPTIONAL,
                $this->newBlockDescription
            )
            ->addOption(
                $this->optionTarget,
                null,
                InputOption::VALUE_OPTIONAL,
                $this->optionTargetDescription
            )
            ->addOption(
                $this->optionJs,
                null,
                InputOption::VALUE_NONE,
                $this->optionJsDescription
            )
            ->addOption(
                $this->optionTheme,
                null,
                InputOption::VALUE_OPTIONAL,
                $this->optionThemeDescription
            )
        ;
    }

    protected function command_init()
    {
    	$action = $this->input->getArgument( $this->action );
//		ShovePrint::info("Command: Block!");
//		ShovePrint::info("Action: " . $action);

		if ( method_exists( get_class($this), $action ) ) {
			$this->$action();
		}
    }


    private function create () {
		$js = $this->input->getOption($this->optionJs);

    	ShovePrint::info("✓ Module Created");

		if ( $js ) {
			ShovePrint::info("JS: " . $js);
		}
	}

	private function list () {
		ShovePrint::info("✓ Module list");

	}

	private function clone () {
		ShovePrint::info("✓ Module Cloned");
	}

	private function rename () {
		ShovePrint::info("✓ Module renamed");

	}

	private function delete () {
		$helper = $this->getHelper('question');
		$question = new ConfirmationQuestion('<comment>!! Are you sure you want to delete the block ?? (y/n) </comment>', false);
		$confirm = $helper->ask($this->input, $this->output, $question);

		if ($confirm == 'y' || $confirm == "yes"){
			ShovePrint::info("✓ Module deleted");

			ShovePrint::info($this->default_messages['tasks_ready']);
		}else{
			ShovePrint::print("<comment>Action canceled</comment>. <info>Your block is safe =)</info>", 'comment');
		}
	}


	private function import () {
		ShovePrint::info("✓ Module imported");

	}


	protected function clean()
	{
		$helper = $this->getHelper('question');
		$question = new ConfirmationQuestion('<comment>!! Are you sure you want to delete all module files?? (y/n) </comment>', false);
		$confirm = $helper->ask($this->input, $this->output, $question);

		if ($confirm == 'y' || $confirm == "yes"){
			ShovePrint::info("✓ Modules cleaned");

			ShovePrint::info($this->default_messages['tasks_ready']);
		}else{
			ShovePrint::print("<comment>Action canceled</comment>. <info>Your files are safe =)</info>", 'comment');
		}

	}

}
