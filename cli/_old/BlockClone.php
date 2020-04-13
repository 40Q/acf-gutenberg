<?php
namespace ACFB\CLI;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BlockClone extends AcfgbCommand
{
    protected $commandName = 'clone';
    protected $commandDescription = "Import blocks from ACFGB Block templates";

    protected $commandArgumentBlock = "block";
    protected $commandArgumentBlockDescription = "Block name to clone";

    protected $commandArgumentNewName= "new_name";
    protected $commandArgumentNewNameDescription = "New block name to clone";



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
                $this->commandArgumentNewName,
                InputArgument::OPTIONAL,
                $this->commandArgumentNewNameDescription
            )
        ;
    }

    protected function command_init()
    {

        $block_to_clone = $this->block_labels->slug;
        $blocks_dir = $this->get_target_path();

        if ($this->block_exist($block_to_clone)){
            $this->set_block_labels($this->input->getArgument($this->commandArgumentNewName));
            $slug = $this->block_labels->slug;
            if (!$this->block_exist($slug)){

                $this->clone_block($block_to_clone, $blocks_dir);

                // Rename blade file
                $this->fileManager()->rename_file(
                    $blocks_dir.$slug."/".$block_to_clone.".blade.php",
                    $blocks_dir.$slug."/".$slug.".blade.php"
                );

                // Rename php class file
                $this->fileManager()->rename_file(
                    $blocks_dir.$slug."/".$block_to_clone.".class.php",
                    $blocks_dir.$slug."/".$slug.".class.php"
                );

                // Rename scss file
                $this->fileManager()->rename_file(
                    $blocks_dir.$slug."/_".str_replace('-', '_', $block_to_clone).".scss",
                    $blocks_dir.$slug."/".$this->block_labels->scss_file.".scss"
                );

                // Rename PHP Class
                $this->rename_clone_php_class(
                    $blocks_dir.$slug."/".$slug.".class.php",
                    $block_to_clone
                );

                // Rename css class
                $this->rename_clone_css_class(
                    $blocks_dir.$slug."/".$this->block_labels->scss_file.".scss",
                    $block_to_clone
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
