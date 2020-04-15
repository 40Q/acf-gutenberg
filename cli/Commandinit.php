<?php
namespace Shove\CLI;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class CommandInit extends ShoveCLI
{
    protected $commandName = 'init';
    protected $commandDescription = 'Generate new GB block with ACF';


    protected function configure()
    {
        $this->theme_blocks_dir = get_template_directory().'/acf-gutenberg/blocks/';
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
        ;
    }

    protected function command_init()
    {
		ShovePrint::title('ACFGB Init tasks');
		ShovePrint::message(" ✓ <info>Check theme path</info>: {$this->path('theme')}");

		$response = $this->ask_yes_no();

		if ( $this->response_yes( $response ) ){

			$this->create_plugin_dir_in_theme();
			$this->copy_plugin_dir_in_theme();
			$this->create_block_scss_file();
			$this->import_blocks_scss_in_main();

			// Import block CLI file to theme
//			$this->import_block_cli_file($this->theme_path);

			ShovePrint::br();
			ShovePrint::message( $this->get_message('tasks_ready') );
			$this->task_resume();
			ShovePrint::br();
		}else{
			ShovePrint::br();
			ShovePrint::comment( $this->get_message('action_canceled') );
		}
    }

	public function create_plugin_dir_in_theme() {
		$destination = $this->path( 'theme.plugin' );

		ShovePrint::br();
		ShovePrint::subtitle('Start creating folders');

		// Create plugin directory
		$error = FileManager::create_dir( $destination );
		if ( $error ) {
			ShovePrint::error( $error );
			$this->add_task( false );
		} else {
			$this->add_task();
			ShovePrint::info(' ✓ Created ACFGB folder in theme directory: /acf-gutenberg/');
		}

		// Create blocks directory
		$error = FileManager::create_dir( $destination . 'blocks' );
		if ( ! $error ) {
			ShovePrint::info(' ✓ Created Blocks folder in theme directory: /acf-gutenberg/blocks');
			$this->add_task();
		}

		// Create config directory
		$error = FileManager::create_dir( $destination . 'config' );
		if ( ! $error ) {
			ShovePrint::info(' ✓ Created Config folder in theme directory: /acf-gutenberg/config');
			$this->add_task();
		}

		// Create components directory
		$error = FileManager::create_dir( $destination . 'components' );
		if ( ! $error ) {
			ShovePrint::info(' ✓ Created Components folder in theme directory: /acf-gutenberg/components');
			$this->add_task();
		}

		// Create modules directory
		$error = FileManager::create_dir( $destination . 'modules' );
		if ( ! $error ) {
			ShovePrint::info(' ✓ Created Modules folder in theme directory: /acf-gutenberg/modules');
			$this->add_task();
		}
	}

	public function copy_plugin_dir_in_theme(){
		$origin      = $this->path('stubs.plugin' );
		$destination = $this->path( 'theme.plugin' );

		if ( is_dir( $this->path( 'theme.plugin' ) ) ) {

			ShovePrint::br();
			ShovePrint::subtitle('Start importing folders files');


			// Import blocks directory
			$error = FileManager::copy_dir( $origin . 'blocks', $destination );
			if ($error){
				ShovePrint::error($error);
				$this->add_task( false );
			} else {
				// Check blocks folder
				$dir = $this->path( 'theme.blocks' );
				if ( is_dir( $dir ) && ! FileManager::is_dir_empty( $dir ) ) {
					ShovePrint::info(' ✓ Imported blocks folder in: ' . $dir );
					$this->add_task();
				} else {
					ShovePrint::error(' x Blocks folder is missing in: ' . $dir );
					$this->add_task( false );
				}
			}

			// Import config directory
			$error = FileManager::copy_dir( $origin . 'config', $destination );
			if ($error){
				ShovePrint::error($error);
				$this->add_task( false );
			} else {
				// Check config folder
				$dir = $this->path( 'theme.config' );
				if ( is_dir( $dir ) && ! FileManager::is_dir_empty( $dir ) ) {
					ShovePrint::info(' ✓ Imported config folder in: ' . $dir );
					$this->add_task();
				} else {
					ShovePrint::error(' x Config folder is missing in: ' . $dir );
					$this->add_task( false );
				}
			}

			// Import components directory
			$error = FileManager::copy_dir( $origin . 'components', $destination );
			if ($error){
				ShovePrint::error($error);
				$this->add_task( false );
			} else {
				// Check components folder
				$dir = $this->path( 'theme.components' );
				if ( is_dir( $dir ) && ! FileManager::is_dir_empty( $dir ) ) {
					ShovePrint::info(' ✓ Imported components folder in: ' . $dir );
					$this->add_task();
				} else {
					ShovePrint::error(' x Components folder is missing in: ' . $dir );
					$this->add_task( false );
				}
			}

			// Import modules directory
			$error = FileManager::copy_dir( $origin . 'modules', $destination );
			if ($error){
				ShovePrint::error($error);
				$this->add_task( false );
			} else {
				// Check modules folder
				$dir = $this->path( 'theme.modules' );
				if ( is_dir( $dir ) && ! FileManager::is_dir_empty( $dir ) ) {
					ShovePrint::info(' ✓ Imported modules folder in: ' . $dir );
					$this->add_task();
				} else {
					ShovePrint::error(' x Modules folder is missing in: ' . $dir );
					$this->add_task( false );
				}
			}

		} else {
				ShovePrint::error('ACFG directory doesn`t exists in: ' . $destination);
				$this->add_task( false );
				$this->add_task( false );
				$this->add_task( false );
				$this->add_task( false );
		}
	}

	public function create_block_scss_file(){

		ShovePrint::br();
		ShovePrint::subtitle('Start importing assets');

		$assets = $this->path('theme.assets');

		if ( is_dir( $assets . 'styles' ) ) {
			$origin      = $this->path('stubs.block.scss');
			$destination = $assets . 'styles/';
			// Import blocks directory
			$error = FileManager::copy_file( $origin, $destination );
			if ($error){
				ShovePrint::error($error);
				$this->add_task( false );
			} else {
				ShovePrint::info(' ✓ Created scss file in theme assets folder: ' . $destination . 'blocks.scss' );
				$this->add_task();
			}

		} else {
			ShovePrint::error('Styles directory not founded in: ' . $assets );
			ShovePrint::error(' x blocks.css is missing !' );
			$this->add_task( false );
		}
	}

	public function import_blocks_scss_in_main(){
    	$file_name = false;
    	$css_file  = $this->path('theme.assets') .'styles/main.scss';

    	// Check if main.scss exists
		if ( file_exists( $css_file) ) {
			ShovePrint::info(' ✓ main.scss file founded' );
			$file_name = 'main.scss';
		} else {
			$css_file = false;
		}

		// Check if app.scss exists
		if ( ! $css_file ) {
			$css_file = $this->path('theme.assets') .'styles/app.scss';

			if ( file_exists( $css_file) ) {
				ShovePrint::info(' ✓ app.scss file founded' );
				$file_name = 'app.scss';
			} else {
				ShovePrint::error(' x main.scss or app.scss file not founded' );
				ShovePrint::warning('block.css was not imported. Import the block file into your main style file manually.' );
			}
		}

		// Import block.scss file
		if ( $css_file ) {
			$error = FileManager::edit_file( 'add_to_bottom', $css_file, [
				"/** ACF Gutenberg Block */",
				"@import 'blocks';",
			]);
			if ($error){
				ShovePrint::error($error);
			} else {
				ShovePrint::info(' ✓ block.scss imported in ' . $file_name );
				$this->add_task();
			}
		}

		if ( ! $css_file || $error ) {
			$this->add_task( false );
		}

	}

}
