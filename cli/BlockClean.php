<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BlockClean extends Command
{
    protected $commandName = 'clean';
    protected $commandDescription = "Clean ACF Gutenberg config";


    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $response ='';
        if (function_exists('get_template_directory')){
            $response.= $this->delete_block_dir_in_theme();
            $response.= $this->delete_block_scss_file();
//            $response.= $this->delete_blocks_scss_in_main();
        }else{
            $response = 'WordPress WordPress has not been loaded. This command need use get_template_directory().';
        }
        $output->writeln($response);
    }

    public function delete_block_dir_in_theme(){
        $target = get_template_directory().'/acf-gutenberg/';
        if (is_dir($target)){
            exec("rm -rf $target");
            $text = 'Deleted blocks folder in theme';
        }else{
            $text = 'Blocks folder has not exists';
        }
        return $text;
    }

    public function delete_block_scss_file(){
        $text= "\n";
        $target = get_template_directory().'/assets/styles/blocks.scss';
        if (file_exists($target)){
            exec("rm $target");
            $text.= 'Deleted scss file in theme assets folder';
        }else{
            $text.= 'The scss file has not exists in theme';
        }
        return $text;
    }

    public function delete_blocks_scss_in_main(){
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
