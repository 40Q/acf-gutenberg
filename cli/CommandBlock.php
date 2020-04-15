<?php
namespace Shove\CLI;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class CommandBlock extends ShoveCLI
{
    protected $commandName = 'block';
    protected $commandDescription = "Generate new GB block with ACF";

	protected $action = "action";
	protected $actionDescription = "Action for block command";

    protected $newBlock = "name";
    protected $newBlockDescription = "Name for new block";

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
				$this->action,
				InputArgument::REQUIRED,
				$this->actionDescription
			)
            ->addArgument(
                $this->newBlock,
                InputArgument::OPTIONAL,
                $this->newBlockDescription
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
    	$action = $this->input()->getArgument( $this->action );
//		ShovePrint::info("Command: Block!");
//		ShovePrint::info("Action: " . $action);

		if ( method_exists( get_class($this), $action ) ) {
			$this->$action();
		}
    }


    private function create () {
		$js = $this->input()->getOption($this->optionJs);

    	ShovePrint::info("✓ Block Created");

		if ( $js ) {
			ShovePrint::info("JS: " . $js);
		}
		//        if ($this->input()->getArgument($this->commandArgumentName)) {
//
//            if (!$this->block_exist($this->block_labels->slug)){
//                ShovePrint::info("------ Init block create tasks ------");
//
//                // Set block slug
//                $slug = $this->block_labels->slug;
//                $js = $this->input()->getOption($this->optionJs);
//
//                // Get block dir by target
//                $blocks_dir = $this->get_target_path();
//
//                // Import block base from _base dir
//                $this->import_block_base($blocks_dir);
//
//                // Rename blade file
//                $this->fileManager()->rename_file(
//                    $blocks_dir.$slug."/block_base.blade.php",
//                    $blocks_dir.$slug."/".$slug.".blade.php"
//                );
//
//                // Rename php class file
//                $this->fileManager()->rename_file(
//                    $blocks_dir.$slug."/block_base.class.php",
//                    $blocks_dir.$slug."/".$slug.".class.php"
//                );
//
//                // Rename scss file
//                $this->fileManager()->rename_file(
//                    $blocks_dir.$slug."/_block_base.scss",
//                    $blocks_dir.$slug."/".$this->block_labels->scss_file.".scss"
//                );
//
//
//                // Rename PHP Class
//                $this->rename_block_base_php_class(
//                    $blocks_dir.$slug."/".$slug.".class.php",
//                    $this->block_labels->php_class,
//                    $this->block_labels->title
//                );
//
//                // Rename css class
//                $this->rename_block_base_css_class(
//                    $blocks_dir.$slug."/".$this->block_labels->scss_file.".scss",
//                    $this->block_labels->css_class
//                );
//
//                // Add new block css to main Blocks.scss
//                $this->add_block_styles_to_blocks_scss($blocks_dir.$slug."/_".$this->block_labels->scss_file.".scss");
//
//
//                if ($js) {
//                    // If isset JS. Import JS file base.
//                    $this->import_js($blocks_dir, $this->block_labels->slug, $this->block_labels->php_class);
//                }
//
//                ShovePrint::info($this->get_message('tasks_ready'));
//
//            }else{
//                ShovePrint::error(
//                    "ERROR!. The block already exists",
//                    'error');
//            }
//        }else{
//            ShovePrint::error(
//                "Need name",
//                'error');
//        }
	}

	private function list () {
		ShovePrint::info("✓ Block list");

//		$target = false;
//		if ($this->input()->getOption($this->optionTarget)) {
//			$target = $this->input()->getOption($this->optionTarget);
//		}
//		$blocks = $this->get_blocks($target);
//		$i = 0;
//		$text = '';
//		foreach ($blocks as $block){
//			if (strpos($block, 'Blocks in') === false){ $i++; }
//			if ($i > 0){ $text.= "\n"; }
//			if (strpos($block, 'Blocks in') === false){ $text.= "    ".$i.". "; }
//			$text.= $block;
//		}
//		$this->output()->writeln($text);
	}


	private function clone () {
		ShovePrint::info("✓ Block Cloned");
//		$block_to_clone = $this->block_labels->slug;
//		$blocks_dir = $this->get_target_path();
//
//		if ($this->block_exist($block_to_clone)){
//			$this->set_block_labels($this->input()->getArgument($this->commandArgumentNewName));
//			$slug = $this->block_labels->slug;
//			if (!$this->block_exist($slug)){
//
//				$this->clone_block($block_to_clone, $blocks_dir);
//
//				// Rename blade file
//				$this->fileManager()->rename_file(
//					$blocks_dir.$slug."/".$block_to_clone.".blade.php",
//					$blocks_dir.$slug."/".$slug.".blade.php"
//				);
//
//				// Rename php class file
//				$this->fileManager()->rename_file(
//					$blocks_dir.$slug."/".$block_to_clone.".class.php",
//					$blocks_dir.$slug."/".$slug.".class.php"
//				);
//
//				// Rename scss file
//				$this->fileManager()->rename_file(
//					$blocks_dir.$slug."/_".str_replace('-', '_', $block_to_clone).".scss",
//					$blocks_dir.$slug."/".$this->block_labels->scss_file.".scss"
//				);
//
//				// Rename PHP Class
//				$this->rename_clone_php_class(
//					$blocks_dir.$slug."/".$slug.".class.php",
//					$block_to_clone
//				);
//
//				// Rename css class
//				$this->rename_clone_css_class(
//					$blocks_dir.$slug."/".$this->block_labels->scss_file.".scss",
//					$block_to_clone
//				);
//
//				// Add new block css to main Blocks.scss
//				$this->add_block_styles_to_blocks_scss($blocks_dir.$slug."/_".$this->block_labels->scss_file.".scss");
//
//
//				ShovePrint::info($this->get_message('tasks_ready'));
//
//			}else{
//				ShovePrint::error("ERROR!. The block already exists");
//			}
//		}else{
//			ShovePrint::error("Block template not exists");
//		}
	}

	private function rename () {
		ShovePrint::info("✓ Block renamed");

	}

	private function delete () {
		$helper = $this->getHelper('question');
		$question = new ConfirmationQuestion('<comment>!! Are you sure you want to delete the block ?? (y/n) </comment>', false);
		$confirm = $helper->ask($this->input(), $this->output(), $question);

		if ($confirm == 'y' || $confirm == "yes"){
			ShovePrint::info("✓ Block deleted");

			ShovePrint::info($this->get_message('tasks_ready'));
		}else{
			ShovePrint::comment("<comment>Action canceled</comment>. <info>Your block is safe =)</info>");
		}
	}


	private function import () {
		ShovePrint::info("✓ Block imported");

	}




	protected function clean()
	{
		$helper = $this->getHelper('question');
		$question = new ConfirmationQuestion('<comment>!! Are you sure you want to delete all block files?? (y/n) </comment>', false);
		$confirm = $helper->ask($this->input(), $this->output(), $question);

		if ($confirm == 'y' || $confirm == "yes"){
			ShovePrint::info("✓ Blocks cleaned");

//				ShovePrint::info("------ Init ACFGB Clean ------");
//
//				// Delete block dir in theme
//				$this->delete_block_dir_in_theme();
//
//				// Delete blocks scss file in theme
//				$this->delete_block_scss_file();
//
//				// Delete block cli file in theme
//				$this->delete_block_cli_file();
//
//				//$this->delete_blocks_scss_in_main();
//				ShovePrint::comment(
//					" - IMPORTANT! Remember delete block scss file reference in main.scss",
//					'comment');
//				ShovePrint::comment(" - IMPORTANT! If you are using custom JS, remember delete JS routes in main.js",
//					'comment');

			ShovePrint::info($this->get_message('tasks_ready'));
		}else{
			ShovePrint::comment("<comment>Action canceled</comment>. <info>Your files are safe =)</info>");
		}

	}

	public function delete_block_dir_in_theme(){

		$error = $this->fileManager()->delete_dir(
			$this->theme_plugin_dir,
			"Delete acf-gutenberg folder in theme"
		);

		if ($error){
			ShovePrint::error($error);
		}else{
			ShovePrint::info(
				" ✓ ACFGB folder deleted");
		}
	}

	public function delete_block_scss_file(){
		$error = $this->fileManager()->delete_file(
			get_template_directory(). '/assets/styles/blocks.scss',
			"Delete blocks scss file in theme"
		);

		if ($error){
			ShovePrint::error($error);
		}else{
			ShovePrint::info(" ✓ Blocks.scss file deleted");
		}
	}

	public function delete_block_cli_file(){
		$error = $this->fileManager()->delete_file(
			get_theme_file_path(). '/block',
			"Delete blocks cli file in theme"
		);

		if ($error){
			ShovePrint::error($error);
		}else{
			ShovePrint::info(" ✓ Blocks CLI file deleted");
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
