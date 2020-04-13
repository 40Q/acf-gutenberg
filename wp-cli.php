<?php

use Symfony\Component\Console\Application;
use Shove\CLI\ShoveCLI;
use Shove\CLI\ShoveHandler;
use Shove\CLI\CommandBlock;
use Shove\CLI\CommandTemplate;
use Shove\CLI\CommandInit;
use Shove\CLI\CommandClean;
use Shove\CLI\CommandComponent;
use Shove\CLI\CommandModules;


\WP_CLI::add_command( 'shove', function( $args, $assoc_args ) {
	require __DIR__ . '/vendor/autoload.php';
	require __DIR__ . '/../../../wp/wp-load.php';

	$ShoveCLI = new Application();

	# add our commands
	require __DIR__ . '/cli/FileManager.php';
	require __DIR__ . '/cli/ShoveCLI.php';

	require __DIR__ . '/cli/ShoveHandler.php';
	$ShoveCLI->add(new ShoveHandler() );

	require __DIR__ . '/cli/CommandInit.php';
	$ShoveCLI->add(new CommandInit() );
	require __DIR__ . '/cli/CommandClean.php';
	$ShoveCLI->add(new CommandClean() );
	require __DIR__ . '/cli/CommandTemplate.php';
	$ShoveCLI->add(new CommandTemplate() );

	require __DIR__ . '/cli/CommandBlock.php';
	$ShoveCLI->add(new CommandBlock() );
	require __DIR__ . '/cli/CommandComponent.php';
	$ShoveCLI->add(new CommandComponent() );
	require __DIR__ . '/cli/CommandModules.php';
	$ShoveCLI->add(new CommandModules() );

	$ShoveCLI->run();
});
