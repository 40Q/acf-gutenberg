<?php
namespace Shove\CLI;

use Roots;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class ShoveHandler extends ShoveCLI
{
    protected $commandName = 'shove';
    protected $commandDescription = "Shove CLI";

	protected $ShoveCommand = "shove-command";
	protected $ShoveCommandDescription = 'Shove Command';

    protected $action = "action";
    protected $actionDescription = 'Shove action';

	protected $actionParam_1 = "param_1";
	protected $actionParam_1_Description = 'Shove action param 1';

	protected $actionParam_2 = "param_2";
	protected $actionParam_2_Description = 'Shove action param 2';

	protected $optionJs = "js"; // should be specified like "create {BlockName} --js"
	protected $optionJsDescription = 'If is set, create js route file for the block.';

    protected function configure()
    {
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
			->addArgument(
				$this->ShoveCommand,
				InputArgument::REQUIRED,
				$this->ShoveCommandDescription
			)
            ->addArgument(
                $this->action,
				InputArgument::OPTIONAL,
                $this->actionDescription
            )
            ->addArgument(
                $this->actionParam_1,
				InputArgument::OPTIONAL,
                $this->actionParam_1_Description
            )
            ->addArgument(
                $this->actionParam_2,
				InputArgument::OPTIONAL,
                $this->actionParam_2_Description
            )
			->addOption(
				$this->optionJs,
				null,
				InputOption::VALUE_NONE,
				$this->optionJsDescription
			)
        ;
    }

	/**
	 * Return first doc comment found in this file.
	 *
	 * @return string
	 */
	function getFileCommentBlock($file_name)
	{
		$Comments = array_filter(
			token_get_all( file_get_contents( $file_name ) ),function($entry) {
			return $entry[0] == T_COMMENT;
		}
		);
		$fileComment = array_shift( $Comments );
		return $fileComment[1];
	}

    protected function command_init() {
		$command_exists = false;
    	$shove_command  = $this->input()->getArgument( $this->ShoveCommand );
		$action         = $this->input()->getArgument( $this->action );
		$param          = $this->input()->getArgument( $this->actionParam_1 );
		$param_2        = $this->input()->getArgument( $this->actionParam_2 );

//		ShovePrint::message('Flag: Builder');

		switch ( $shove_command ) {

			/** Command: Init */
			case 'init':
				$command_exists = true;
				$command        = $this->getApplication()->find('init');
				$arguments      = [
					'command' => 'init',
				];
				break;

			/** Command: Clean */
			case 'clean':
				$command_exists = true;
				$command        = $this->getApplication()->find('clean');
				$arguments      = [
					'command' => 'clean',
				];
				break;

			/** Command: Config */
			case 'config':
				$command_exists = true;
				$command        = $this->getApplication()->find('config');
				$arguments      = [
					'command' => 'config',
				];
				break;

			/** Command: Template */
			case 'template':
				$command        = $this->getApplication()->find('template');
				$arguments      = [
					'command' => 'template',
				];

				if ( isset( $action ) && is_string( $action ) && ! is_numeric( $action ) ) {
					$arguments['action'] = $action;

					switch ($action) {

						// wp shove template list
						case 'list':
							$command_exists = true;
							break;

						// wp shove template list
						case 'info':
							if ( isset( $param ) ) {
								$command_exists = true;
							} else {
								ShovePrint::error("Name is required. Usage: wp shove template list {block-name}");
								$command_exists      = true;
								$arguments['action'] = 'list';
								ShovePrint::info(" ");
								ShovePrint::info("Available blocks to import:");
							}
							break;
					}
				} else {
					ShovePrint::error("Action missing for template command");
					ShovePrint::info("Usages:");
					ShovePrint::info("wp shove template list");
					ShovePrint::info("wp shove template info {block-name}");
				}

				break;


			/** Command: Block */
			case 'block':
				$command   = $this->getApplication()->find('block');
				$arguments = [
					'command' => 'block',
				];

				if ( isset( $action ) && is_string( $action ) && ! is_numeric( $action ) ) {
					$arguments['action'] = $action;

					switch ( $action ) {

						// wp shove block create
						case 'create':
							if ( isset( $param ) ) {
								if ( is_string( $param ) && ! is_numeric( $param ) ) {
									$js = $this->option($this->optionJs);
									if ( isset( $js ) && $js ) {
										$command_exists = true;
										$arguments['--js'] = true;
										ShovePrint::comment( 'Run block create, new block name: ' . $param . 'with js file');
									} else {
										$command_exists = true;
										ShovePrint::comment( 'Run block create, new block name: ' . $param );
									}
								} else {
									ShovePrint::error("Name should be string. Usage: wp shove block create {block-name}");
								}
							} else {
								ShovePrint::error("Name is required. Usage: wp shove block create {block-name}");
							}
							break;


						// wp shove block create
						case 'list':
							$command_exists = true;
							break;


						// wp shove block clone
						case 'clone':
							if ( isset( $param ) && isset( $param_2 ) ) {
								$command_exists = true;
							} else {
								ShovePrint::error("Name is required. Usage: wp shove block clone {block-name} {new-block-name}");
							}
							break;


						// wp shove rename clone
						case 'rename':
							if ( isset( $param ) && isset( $param_2 ) ) {
								$command_exists = true;
							} else {
								ShovePrint::error("Name is required. Usage: wp shove block rename {block-name} {new-block-name}");
							}
							break;


						// wp shove block delete
						case 'delete':
							if ( isset( $param ) ) {
								$command_exists = true;
							} else {
								ShovePrint::error("Name is required. Usage: wp shove block delete {block-name}");
							}
							break;

						// wp shove block clean
						case 'clean':
							$command_exists = true;
							break;

						// wp shove block import
						case 'import':
							if ( isset( $param ) ) {
								$command_exists = true;
							} else {
								ShovePrint::error("Name is required. Usage: wp shove block import {block-name}");
								$command_exists = true;
								$arguments['action'] = 'list';
								ShovePrint::info(" ");
								ShovePrint::info("Available blocks to import:");

							}
							break;


						// Error! command not found
						default:
							\WP_CLI::error("Action {$action} not found in block command!");
							break;
					}
				} else {
					ShovePrint::error("Action missing for block command");
				}

				break;


			/** Command: Component */
			case 'component':
				$command   = $this->getApplication()->find('component');
				$arguments = [
					'command' => 'component',
				];

				if ( isset( $action ) && is_string( $action ) && ! is_numeric( $action ) ) {
					$arguments['action'] = $action;

					switch ( $action ) {

						// wp shove component create
						case 'create':
							if ( isset( $param ) ) {
								if ( is_string( $param ) && ! is_numeric( $param ) ) {
									$js = $this->option($this->optionJs);
									if ( isset( $js ) && $js ) {
										$command_exists = true;
										$arguments['--js'] = true;
										ShovePrint::comment( 'Run component create, new component name: ' . $param . 'with js file' );
									} else {
										$command_exists = true;
										ShovePrint::comment( 'Run component create, new component name: ' . $param );
									}
								} else {
									ShovePrint::error("Name should be string. Usage: wp shove component create {component-name}");
								}
							} else {
								ShovePrint::error("Name is required. Usage: wp shove component create {component-name}");
							}
							break;


						// wp shove component create
						case 'list':
							$command_exists = true;
							break;


						// wp shove component clone
						case 'clone':
							if ( isset( $param ) && isset( $param_2 ) ) {
								$command_exists = true;
							} else {
								ShovePrint::error("Name is required. Usage: wp shove component clone {component-name} {new-component-name}");
							}
							break;


						// wp shove rename clone
						case 'rename':
							if ( isset( $param ) && isset( $param_2 ) ) {
								$command_exists = true;
							} else {
								ShovePrint::error("Name is required. Usage: wp shove component rename {component-name} {new-component-name}");
							}
							break;


						// wp shove component delete
						case 'delete':
							if ( isset( $param ) ) {
								$command_exists = true;
							} else {
								ShovePrint::error("Name is required. Usage: wp shove component delete {component-name}");
							}
							break;

						// wp shove component clean
						case 'clean':
							$command_exists = true;
							break;

						// wp shove component import
						case 'import':
							if ( isset( $param ) ) {
								$command_exists = true;
							} else {
								ShovePrint::error("Name is required. Usage: wp shove component import {component-name}");
								$command_exists = true;
								$arguments['action'] = 'list';
								ShovePrint::info(" ");
								ShovePrint::info("Available components to import:");

							}
							break;


						// Error! command not found
						default:
							\WP_CLI::error("Action {$action} not found in component command!");
							break;
					}
				} else {
					ShovePrint::error("Action missing for component command");
				}

				break;


			/** Command: Module */
			case 'module':
				$command   = $this->getApplication()->find('module');
				$arguments = [
					'command' => 'module',
				];

				if ( isset( $action ) && is_string( $action ) && ! is_numeric( $action ) ) {
					$arguments['action'] = $action;

					switch ( $action ) {

						// wp shove module create
						case 'create':
							if ( isset( $param ) ) {
								if ( is_string( $param ) && ! is_numeric( $param ) ) {
									$js = $this->option($this->optionJs);
									if ( isset( $js ) && $js ) {
										$command_exists = true;
										$arguments['--js'] = true;
										ShovePrint::comment( 'Run module create, new module name: ' . $param . 'with js file' );
									} else {
										$command_exists = true;
										ShovePrint::comment( 'Run module create, new module name: ' . $param );
									}
								} else {
									ShovePrint::error("Name should be string. Usage: wp shove module create {module-name}");
								}
							} else {
								ShovePrint::error("Name is required. Usage: wp shove module create {module-name}");
							}
							break;


						// wp shove module create
						case 'list':
							$command_exists = true;
							break;


						// wp shove module clone
						case 'clone':
							if ( isset( $param ) && isset( $param_2 ) ) {
								$command_exists = true;
							} else {
								ShovePrint::error("Name is required. Usage: wp shove module clone {module-name} {new-module-name}");
							}
							break;


						// wp shove rename clone
						case 'rename':
							if ( isset( $param ) && isset( $param_2 ) ) {
								$command_exists = true;
							} else {
								ShovePrint::error("Name is required. Usage: wp shove module rename {module-name} {new-module-name}");
							}
							break;


						// wp shove module delete
						case 'delete':
							if ( isset( $param ) ) {
								$command_exists = true;
							} else {
								ShovePrint::error("Name is required. Usage: wp shove module delete {module-name}");
							}
							break;

						// wp shove module clean
						case 'clean':
							$command_exists = true;
							break;

						// wp shove module import
						case 'import':
							if ( isset( $param ) ) {
								$command_exists = true;
							} else {
								ShovePrint::error("Name is required. Usage: wp shove module import {module-name}");
								$command_exists = true;
								$arguments['action'] = 'list';
								ShovePrint::info(" ");
								ShovePrint::info("Available modules to import:");

							}
							break;


						// Error! command not found
						default:
							\WP_CLI::error("Action {$action} not found in module command!");
							break;
					}
				} else {
					ShovePrint::error("Action missing for module command", );
				}

				break;



			/** Command: Error! not found */
			default:
				ShovePrint::error("Command {$shove_command} not found!");
				break;
		}

		if ( $command_exists ) {

			if (function_exists('get_template_directory')){
				$arguments = new ArrayInput($arguments);
				$command->run($arguments, $this->output());
			}else{
				$error = 'WordPress has not been loaded. Shove CLI needs use get_template_directory() function.';
				ShovePrint::error($error);
			}
		}
    }


}
