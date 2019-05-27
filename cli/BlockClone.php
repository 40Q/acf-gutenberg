<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BlockClone extends AcfgbCommand
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
                InputArgument::OPTIONAL,
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

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $block = $input->getArgument($this->commandArgumentBlock);
        $prefix = $input->getArgument($this->commandArgumentPrefix);
        $target = $this->get_target($input);
        $blocks_dir = $this->get_target_path($target);
        $class_name = $this->name_to_php_class($block);
        $css_class_name = $this->name_to_css_class($block);

        if ($this->block_template_exist($block)){
            $new_block_slug = $this->set_block_slug($block, $prefix);
            if (!$this->block_exist($new_block_slug)){
                $text = 'Import block: '.$new_block_slug.' | prefix: '.$prefix.' | target: '.$target;
                $this->import_block($block, $blocks_dir, $new_block_slug, $prefix);
                if ($prefix){
                    $this->rename_block_class($blocks_dir, $new_block_slug, $class_name, $prefix);
                    $this->rename_block_scss($blocks_dir, $new_block_slug, $css_class_name, $prefix);
                }
                $response = $this->import_scss($blocks_dir, $new_block_slug, $css_class_name);

            }else{
                $text = "ERROR!. The block already exists";
            }
        }else{
            $text = "Block template not exists";
        }


        $output->writeln($text);
    }

    public function set_block_slug($block, $prefix){
        $new_block_name = "acfgb-".$block;
        if ($prefix){
            $new_block_name = $prefix."-".$block;
        }
        return $new_block_name;
    }

    public function import_block($block, $blocks_dir, $slug, $prefix){
        $new_block_dir = $blocks_dir.$slug;
        exec("cp -r $this->blocks_templates_dir/acfgb-$block $new_block_dir");
        if ($prefix){
            if (file_exists($blocks_dir.$slug."/acfgb-$block.blade.php")){
                rename ($blocks_dir.$slug."/acfgb-$block.blade.php", $blocks_dir.$slug."/".$slug.".blade.php");
            }
            if (file_exists($blocks_dir.$slug."/acfgb-$block.class.php")){
                rename ($blocks_dir.$slug."/acfgb-$block.class.php", $blocks_dir.$slug."/".$slug.".class.php");
            }
            if (file_exists($blocks_dir.$slug."/_acfgb_$block.scss")){
                $scss_file = $this->slug_to_css_file($slug);
                rename ($blocks_dir.$slug."/_acfgb_$block.scss", $blocks_dir.$slug."/_".$scss_file.".scss");
            }
        }
    }

    public function rename_block_class($blocks_dir, $slug, $class_name, $prefix){
        if (file_exists($blocks_dir.$slug."/".$slug.".class.php")){
            $file_name = $blocks_dir.$slug."/".$slug.".class.php";
            $block_base = fopen( $file_name, "r");
            $block_base_content = fread($block_base, filesize($file_name));
            fclose($block_base);

            $new_block = $block_base_content;
            $new_block = str_replace("ACFGB", ucfirst($prefix), $new_block);
            $new_block = str_replace("Acfgb", ucfirst($prefix), $new_block);

            $new_block_file = fopen($file_name, 'w+');
            fwrite($new_block_file, $new_block);
            fclose($new_block_file);
        }
    }

    public function rename_block_scss($blocks_dir, $slug, $css_class_name, $prefix){
        $scss_file = $this->slug_to_css_file($slug);
        if (file_exists($blocks_dir.$slug."/_".$scss_file.".scss")){
            $file_name = $blocks_dir.$slug."/_".$scss_file.".scss";
            $block_base = fopen( $file_name, "r");
            $block_base_content = fread($block_base, filesize($file_name));
            fclose($block_base);

            $new_block = $block_base_content;
            $new_block = str_replace("acfgb", $prefix, $new_block);

            $new_block_file = fopen($file_name, 'w+');
            fwrite($new_block_file, $new_block);
            fclose($new_block_file);
        }
    }
}