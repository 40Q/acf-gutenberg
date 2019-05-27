<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BlockCreate extends AcfgbCommand
{
    protected $commandName = 'create';
    protected $commandDescription = "Generate new GB block with ACF";

    protected $commandArgumentName = "name";
    protected $commandArgumentDescription = "Name for new block";

    protected $optionTarget = "target"; // should be specified like "create {BlockName} --target=theme | --target=plugin"
    protected $optionTargetDescription = 'Select target to import block. Can be: theme or plugin';


    protected function configure()
    {
        $this->theme_blocks_dir = get_template_directory().'/acf-gutenberg/blocks/';
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->addArgument(
                $this->commandArgumentName,
                InputArgument::REQUIRED,
                $this->commandArgumentDescription
            )
            ->addOption(
                $this->optionTarget,
                null,
                InputOption::VALUE_OPTIONAL,
                $this->optionTargetDescription
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument($this->commandArgumentName);
        $target = $this->get_target($input);

        if ($name) {
            $title = $this->name_to_title($name);
            $class_name = $this->name_to_php_class($name);
            $css_class_name = $this->name_to_css_class($name);
            $slug = $this->name_to_slug($name);
            $blocks_dir = $this->get_target_path($target);

            if (!$this->block_exist($slug)){
                $this->create_block($blocks_dir, $slug);
                $this->create_block_class_file($blocks_dir, $slug, $class_name, $title);
                $this->create_block_scss_file($blocks_dir, $slug, $css_class_name);
                $response = $this->import_scss($blocks_dir, $slug, $css_class_name);

                $text = 'New block: '.$title.' | class: '.$class_name.' | slug: '.$slug.' | css class: '.$css_class_name;
                $text.= "\n";
                $text.= "New block created in {$target} folder";
                $text.= "\n";
                $text.= $response;
            }else{
                $text = "ERROR!. The block already exists";
            }
        }else{
            $text = 'Need name';
        }


        $output->writeln($text);
    }

}