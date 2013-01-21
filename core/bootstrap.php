<?php

if ( isset( $_GET['argv'] ) && !empty( $_GET['argv'] ) ) {

	// set the vars to empty
	$controller = $method = '';
	$params = array();

	// autoload the current controller
	require_once CORE_ROOT.'/loader.php';
	Loader::register();

	// split the arguments into an array
	$argv = explode( '/', $_GET['argv'] );

	// number of arguments
	$argc = count( $argv );

	// if one or more arguments set the first to controller
	$controller = ( $argc < 0 ) ?: $argv[0];

	// if two or more arguments set the second to method
	$method = ( $argc > 1 && !empty( $argv[1] ) ) ? 'view_'.$argv[1] : 'view_index';

	// if three or more arguments set the rest to params
	$params = ( $argc < 1 ) ?: array_slice( $argv, 2 );
	try{
		if ( class_exists( $controller ) ) {
			$c = new $controller();
			if ( method_exists( $c, $method ) )
				$c->$method( $params );
			else {
				echo 'The view <i>'.$method.'</i> doesnt exists';
			}
		}
		else {
			echo $controller;
			echo 'The file exists but not the class you may have misconfigured your controller';
		}
	}
	catch( \Exception $e ) {
		echo $e->getMessage();
	}


}
