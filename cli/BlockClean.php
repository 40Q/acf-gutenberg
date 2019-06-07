<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BlockClean extends AcfgbCommand
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

    protected function command_init()
    {
        if (function_exists('get_template_directory')){
            $this->print("------ Init ACFGB Clean ------");

            // Delete block dir in theme
            $this->delete_block_dir_in_theme();

            // Delete blocks scss file in theme
            $this->delete_block_scss_file();

            // Delete block cli file in theme
            $this->delete_block_cli_file();

            //$this->delete_blocks_scss_in_main();
            $this->print(
                " - IMPORTANT! Remember delete block scss file reference in main.scss",
                'comment');
            $this->print(" - IMPORTANT! If you are using custom JS, remember delete JS routes in main.js",
                'comment');

            $this->print($this->default_messages['tasks_ready']);
        }else{
            $error = 'WordPress has not been loaded. This command need use get_template_directory().';
            $this->print($error, 'error');
        }

    }

    public function delete_block_dir_in_theme(){
        $error = $this->fileManager()->delete_dir(
            $this->theme_plugin_dir,
            "Delete acf-gutenberg folder in theme"
        );

        if ($error){
            $this->print($error, 'error');
        }else{
            $this->print(
                " ✓ ACFGB folder deleted",
                'info');
        }
    }

    public function delete_block_scss_file(){
        $error = $this->fileManager()->delete_file(
            get_template_directory(). '/assets/styles/blocks.scss',
            "Delete blocks scss file in theme"
        );

        if ($error){
            $this->print($error, 'error');
        }else{
            $this->print(
                " ✓ Blocks.scss file deleted",
                'info');
        }
    }

    public function delete_block_cli_file(){
        $error = $this->fileManager()->delete_file(
            get_theme_file_path(). '/block',
            "Delete blocks cli file in theme"
        );

        if ($error){
            $this->print($error, 'error');
        }else{
            $this->print(
                " ✓ Blocks CLI file deleted",
                'info');
        }
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
