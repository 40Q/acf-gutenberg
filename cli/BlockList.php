<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BlockList extends AcfgbCommand
{
    protected $commandName = 'list';
    protected $commandDescription = "Get blocks created list";

    protected $optionTarget = "target"; // should be specified like "list --target=theme | --target=plugin"
    protected $optionTargetDescription = 'Select target to import block. Can be: theme or plugin';


    protected function configure()
    {
        $this->theme_blocks_dir = get_template_directory().'/acf-gutenberg/blocks/';
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->addOption(
                $this->optionTarget,
                null,
                InputOption::VALUE_OPTIONAL,
                $this->optionTargetDescription
            )
        ;
    }

    protected function command_init()
    {
        $target = false;
        if ($this->input->getOption($this->optionTarget)) {
            $target = $this->input->getOption($this->optionTarget);
        }
        $blocks = $this->get_blocks($target);
        $i = 0;
        $text = '';
        foreach ($blocks as $block){
            if (strpos($block, 'Blocks in') === false){ $i++; }
            if ($i > 0){ $text.= "\n"; }
            if (strpos($block, 'Blocks in') === false){ $text.= "    ".$i.". "; }
            $text.= $block;
        }
        $this->output->writeln($text);
    }
}