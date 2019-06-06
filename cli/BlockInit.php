<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BlockInit extends AcfgbCommand
{
    protected $commandName = 'init';
    protected $commandDescription = "Init ACF Gutenberg config";


    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
        ;
    }

    protected function command_init()
    {
        $response ='';
        if (function_exists('get_template_directory')){

            $this->print("------ ACFGB Init tasks ------");

            // Get block dir by target
            $response.= $this->create_block_dir_in_theme();
            $response.= $this->create_block_scss_file();
            $response.= $this->import_blocks_scss_in_main();
            // Import block CLI file to theme
            $this->import_block_cli_file(get_theme_file_path().'/');

            $this->print($this->default_messages['tasks_ready']);
        }else{
            $error = 'WordPress has not been loaded. This command need use get_template_directory().';
            $this->print($error, 'error');
        }
    }

    public function create_block_dir_in_theme(){
        $acf_dir_base = __DIR__.'/_base/acf-gutenberg';
        $target = get_template_directory();
        if (!is_dir($target.'/acf-gutenberg')){
            exec("cp -r $acf_dir_base $target");
            $this->print(
                " ✓ Created blocks folder in theme",
                'info');
        }else{
            $text = ' ';
            $this->print(
                " - ERROR!. Blocks folder already exists in theme",
                'error');
        }
    }

    public function create_block_scss_file(){
        $text= "\n";
        $scss_file_base = __DIR__.'/_base/blocks.scss';
        $target = get_template_directory().'/assets/styles/blocks.scss';
        if (!file_exists($target)){
            exec("cp -r $scss_file_base $target");
            $this->print(
                " ✓ Created scss file in theme assets folder",
                'info');
        }else{
            $this->print(
                " - ERROR!. The scss file already exists in theme",
                'error');
        }
        return $text;
    }

    public function import_blocks_scss_in_main(){
        $text= "\n";
        $target = get_template_directory().'/assets/styles/main.scss';
        if (file_exists($target)){
            $main_scss = fopen( $target, "a+");
            $main_scss_content="\n \n";
            $main_scss_content.='@import "blocks";';
            fwrite($main_scss, $main_scss_content);
            fclose($main_scss);
            $this->print(
                " ✓ Blocks styles imported into main.scss",
                'info');
        }else{
            $this->print(
                " - ERROR!. Mains scss not found in: '.$target.\". Import blocks.scss in your main.scss",
                'error');
        }
        return $text;
    }
}
