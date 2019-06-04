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
            // Get block dir by target
            $response.= $this->create_block_dir_in_theme();
            $response.= $this->create_block_scss_file();
            $response.= $this->import_blocks_scss_in_main();
            // Import block CLI file to theme
            $this->import_block_cli_file(get_theme_file_path().'/');

        }else{
            $response = 'WordPress has not been loaded. This command need use get_template_directory().';
        }
        $this->output->writeln($response);
    }

    public function create_block_dir_in_theme(){
        $acf_dir_base = __DIR__.'/_base/acf-gutenberg';
        $target = get_template_directory();
        if (!is_dir($target.'/acf-gutenberg')){
            exec("cp -r $acf_dir_base $target");
            $text = 'Created blocks folder in theme';
        }else{
            $text = 'Blocks folder already exists in theme';
        }
        return $text;
    }

    public function create_block_scss_file(){
        $text= "\n";
        $scss_file_base = __DIR__.'/_base/blocks.scss';
        $target = get_template_directory().'/assets/styles/blocks.scss';
        if (!file_exists($target)){
            exec("cp -r $scss_file_base $target");
            $text.= 'Created scss file in theme assets folder';
        }else{
            $text.= 'The scss file already exists in theme';
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

            $text.= 'Blocks styles imported into main.scss';
        }else{
            $text.= 'Mains scss not found in: '.$target.". Import blocks.scss in your main.scss";
        }
        return $text;
    }
}
