<?php
namespace Shove\CLI;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class ShovePrint extends ShoveCLI
{

	protected $commandName = 'print';
	protected $commandDescription = "Shove Print";

	protected function configure()
	{
		$this
			->setName($this->commandName)
			->setDescription($this->commandDescription)
		;
	}

	static function message( $message ) {
		self::print( $message );
	}

	static function info( $message ) {
		self::print( $message, 'info' );
	}

	static function comment( $message ) {
		self::print( $message, 'comment' );
	}

	static function success( $message ) {
		self::print( $message, 'success' );
	}

	static function warning( $message ) {
		self::print( $message, 'warning' );
	}

    static function error( $message ) {
    	self::print( $message, 'error' );
    }

    static function die( $message ) {
    	self::print( $message, 'die' );
    }

	static function title( $title ) {
		self::print('-------- ' . strtoupper( $title ) . ' --------' );
	}

	static function subtitle( $title ) {
		self::print('-- ' . strtoupper( $title ) );
	}

    static function hr() {
		self::print('------------------');
	}

	static function br() {
		self::print(' ');
	}

	static function print($message , $style = false){
		if($style){
			$message = self::get_formatted_message($message, $style);
		}
		parent::$_output->writeln($message);
	}

	static function get_formatted_message($message, $style){
		switch ($style){

			case 'info':
				$tag = 'info';
				$message = "<{$tag}>{$message}</{$tag}>";
				break;
			case 'comment':
				$tag = 'comment';
				$message = "<{$tag}>{$message}</{$tag}>";
				break;
			case 'success':
				$tag = 'info';
				$message = "<{$tag}>Success:</{$tag}> {$message}";
				break;
			case 'warning':
				$tag = 'comment';
				$message = "<{$tag}>Warning:</{$tag}> {$message}";
				break;
			case 'error':
				$tag = 'error';
				$message = "<{$tag}>Error:</{$tag}> {$message}";
				break;
			case 'die':
				$tag = 'error';
				$message = "<{$tag}>{$message}</{$tag}>";
				break;
		}
		return $message;
	}


	static function usage(){

		ShovePrint::title('ShovePrint Usage');
		ShovePrint::br();

		ShovePrint::message('ShovePrint::title( $title )');
		ShovePrint::message('ShovePrint::subtitle( $title )');
		ShovePrint::message('ShovePrint::br()');
		ShovePrint::message('ShovePrint::hr()');
		ShovePrint::hr();
		ShovePrint::br();


		ShovePrint::subtitle('Message types');
		ShovePrint::message('ShovePrint::message( $message )');
		ShovePrint::message(' -> This is a simple message');
		ShovePrint::br();

		ShovePrint::message('ShovePrint::comment( $message )');
		ShovePrint::comment(' -> This is a comment');
		ShovePrint::br();

		ShovePrint::message('ShovePrint::info( $message )');
		ShovePrint::info(' -> This is a info text');
		ShovePrint::br();

		ShovePrint::message('ShovePrint::warning( $message )');
		ShovePrint::warning('This is a warning!');
		ShovePrint::br();

		ShovePrint::message('ShovePrint::success( $message )');
		ShovePrint::success('This is a success message');
		ShovePrint::br();

		ShovePrint::message('ShovePrint::error( $message )');
		ShovePrint::error('This is a error message');
		ShovePrint::br();

		ShovePrint::message('ShovePrint::die( $message )');
		ShovePrint::die('Fatal error: There was an error!');
	}


}
