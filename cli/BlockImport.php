<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class BlockImport extends AcfgbCommand
{
    protected $commandName = 'import';
    protected $commandDescription = "Import blocks from ACFGB Block templates";

    protected $commandArgumentBlock = "block";
    protected $commandArgumentBlockDescription = "Block name to import";

    protected $commandArgumentPrefix = "prefix";
    protected $commandArgumentPrefixDescription = "Block name to import";

    protected $optionTarget = "target"; // should be specified like "import {BlockName} --target=theme | --target=plugin"
    protected $optionTargetDescription = 'Select target to import block. Can be: theme or plugin';


    protected function configure()
    {
        $this->theme_blocks_dir = get_template_directory().'/acf-gutenberg/blocks/';
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
            ->addArgument(
                $this->commandArgumentBlock,
                InputArgument::REQUIRED,
                $this->commandArgumentBlockDescription
            )
            ->addArgument(
                $this->commandArgumentPrefix,
                InputArgument::REQUIRED,
                $this->commandArgumentPrefixDescription
            )
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

        /*
        $this->print($this->commandArgumentPrefix);
        if (!isset($this->commandArgumentPrefix) || $this->commandArgumentPrefix == 'prefix'){
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('Do you want user a prefix for this block?. Enter prefix...', false);
            $prefix = $helper->ask($this->input, $this->output, $question);
            $this->input->setArgument('prefix',$prefix);
            $this->initial_setting();
        }
        */

        $slug = $this->block_labels->slug;
        $template = $this->block_labels->template;
        $blocks_dir = $this->get_target_path();

        if ($this->block_template_exist($template)){
            if (!$this->block_exist($slug)){
                $this->import_template($template, $blocks_dir);

                // Rename blade file
                $this->fileManager()->rename_file(
                    $blocks_dir.$slug."/".$template.".blade.php",
                    $blocks_dir.$slug."/".$slug.".blade.php"
                );

                // Rename php class file
                $this->fileManager()->rename_file(
                    $blocks_dir.$slug."/".$template.".class.php",
                    $blocks_dir.$slug."/".$slug.".class.php"
                );

                // Rename scss file
                $this->fileManager()->rename_file(
                    $blocks_dir.$slug."/_".str_replace('-', '_', $template).".scss",
                    $blocks_dir.$slug."/".$this->block_labels->scss_file.".scss"
                );

                // Rename PHP Class
                $this->rename_template_php_class(
                    $blocks_dir.$slug."/".$slug.".class.php",
                    $this->block_labels->php_class,
                    $this->block_labels->title
                );

                // Rename css class
                $this->rename_template_css_class(
                    $blocks_dir.$slug."/".$this->block_labels->scss_file.".scss",
                    $this->block_labels->css_class
                );

                // Add new block css to main Blocks.scss
                $this->add_block_styles_to_blocks_scss($blocks_dir.$slug."/_".$this->block_labels->scss_file.".scss");

                $this->print($this->default_messages['tasks_ready']);

            }else{
                $this->print(
                    "ERROR!. The block already exists",
                    'error');
            }
        }else{
            $this->print(
                "Block template not exists",
                'error');
        }

    }




}