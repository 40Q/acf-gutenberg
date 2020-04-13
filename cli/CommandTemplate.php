<?php
namespace Shove\CLI;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class CommandTemplate extends ShoveCLI
{
    protected $commandName = 'template';
    protected $commandDescription = "Generate new GB block with ACF";

	protected $action = "action";
	protected $actionDescription = "Action for block command";

    protected $newBlock = "name";
    protected $newBlockDescription = "Name for new block";


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
        ;
    }

    protected function command_init()
    {
    	$action = $this->input->getArgument( $this->action );
//		$this->print("Command: Template!");
//		$this->print("Action: " . $action);

		if ( method_exists( get_class($this), $action ) ) {
			$this->$action();
		}
    }



	private function list () {
		$this->print("✓ Template list");

//		$target = false;
//		if ($this->input->getOption($this->optionTarget)) {
//			$target = $this->input->getOption($this->optionTarget);
//		}
//		$blocks = $this->get_blocks($target);
//		$i = 0;
//		$text = '';
//		foreach ($blocks as $block){
//			if (strpos($block, 'Blocks in') === false){ $i++; }
//			if ($i > 0){ $text.= "\n"; }
//			if (strpos($block, 'Blocks in') === false){ $text.= "    ".$i.". "; }
//			$text.= $block;
//		}
//		$this->output->writeln($text);
	}

	private function info () {
		$this->print("✓ Template info:");

	}
}
