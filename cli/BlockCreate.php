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
        if ($this->input->getArgument($this->commandArgumentName)) {

            if (!$this->block_exist($this->block_labels->slug)){
                $this->print("------ Init block create tasks ------");

                // Set block slug
                $slug = $this->block_labels->slug;
                $js = $this->input->getOption($this->optionJs);

                // Get block dir by target
                $blocks_dir = $this->get_target_path();

                // Import block base from _base dir
                $this->import_block_base($blocks_dir);

                // Rename blade file
                $this->fileManager()->rename_file(
                    $blocks_dir.$slug."/block_base.blade.php",
                    $blocks_dir.$slug."/".$slug.".blade.php"
                );

                // Rename php class file
                $this->fileManager()->rename_file(
                    $blocks_dir.$slug."/block_base.class.php",
                    $blocks_dir.$slug."/".$slug.".class.php"
                );

                // Rename scss file
                $this->fileManager()->rename_file(
                    $blocks_dir.$slug."/_block_base.scss",
                    $blocks_dir.$slug."/".$this->block_labels->scss_file.".scss"
                );


                // Rename PHP Class
                $this->rename_block_base_php_class(
                    $blocks_dir.$slug."/".$slug.".class.php",
                    $this->block_labels->php_class,
                    $this->block_labels->title
                );

                // Rename css class
                $this->rename_block_base_css_class(
                    $blocks_dir.$slug."/".$this->block_labels->scss_file.".scss",
                    $this->block_labels->css_class
                );

                // Add new block css to main Blocks.scss
                $this->add_block_styles_to_blocks_scss($blocks_dir.$slug."/_".$this->block_labels->scss_file.".scss");


                if ($js) {
                    // If isset JS. Import JS file base.
                    $this->import_js($blocks_dir, $this->block_labels->slug, $this->block_labels->php_class);
                }

                $this->print($this->default_messages['tasks_ready']);

            }else{
                $this->print(
                    "ERROR!. The block already exists",
                    'error');
            }
        }else{
            $this->print(
                "Need name",
                'error');
        }
    }

}