<?php
namespace ACFB\CLI;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class BlockInit extends AcfgbCommand
{
    protected $commandName = 'init';
    protected $commandDescription = "Init ACF Gutenberg config";

    protected $optionTheme = "theme";
    protected $optionThemeDescription = 'Select theme to import block.';

    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
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
		$this->print('init!', 'comment');

        $this->set_theme_target();
		$this->print($this->theme_path, 'comment');
        if ($this->theme_path){
            if (function_exists('get_template_directory')){

                $this->print("------ ACFGB Init tasks ------");
                $this->print(" ✓ <info>Check theme path</info>: {$this->theme_path}");

                $helper = $this->getHelper('question');
                $question = new ConfirmationQuestion('Continue with this action? (y/n)', false);
                $confirm = $helper->ask($this->input, $this->output, $question);

                if ($confirm == 'y' || $confirm == "yes"){

                    $this->create_block_dir_in_theme();
                    $this->create_block_scss_file();
                    $this->import_blocks_scss_in_main();
                    // Import block CLI file to theme
                    $this->import_block_cli_file($this->theme_path);

                    $this->print($this->default_messages['tasks_ready']);
                }else{
                    $this->print("Action canceled", 'comment');
                }


            }else{
                $error = 'WordPress has not been loaded. This command need use get_template_directory().';
                $this->print($error, 'error');
            }
        }
    }

    public function create_block_dir_in_theme(){
        $acf_dir_base = __DIR__.'/_base/acf-gutenberg';
        $target = $this->theme_path;
        if (!is_dir($target.'/acf-gutenberg')){
            exec("cp -r $acf_dir_base $target");
            $this->print(
                " ✓ Created ACFGB folder in theme directory: /acf-gutenberg/",
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
        $target = $this->theme_path.'/assets/styles/blocks.scss';
        if (!file_exists($target)){
            exec("cp -r $scss_file_base $target");
            $this->print(
                " ✓ Created scss file in theme assets folder: blocks.scss",
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
        $target = $this->theme_path.'/assets/styles/main.scss';
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