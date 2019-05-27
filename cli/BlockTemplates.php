<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BlockTemplates extends AcfgbCommand
{
    protected $commandName = 'template:list';
    protected $commandDescription = "Get templates blocks list";


    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $blocks = $this->get_blocks_templates();
        $text = 'Available blocks to import';
        $i = 0;
        foreach ($blocks as $block){
            $i++;
            $text.= "\n";
            $text.= "  ".$i.". ".$block;
        }
        $output->writeln($text);
    }
}