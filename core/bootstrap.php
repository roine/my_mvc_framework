<?php

// autoload the current controller
require_once CORE_ROOT.'/loader.php';
Loader::register();

// set the vars to empty
$controller = $method = NULL;
$params = array();

// Routes first
require_once CONFIG_ROOT."/routes.php";
foreach(Routes::$routes as $from => $to){
  // whether use default route or redirect
	$then = $to['type'] === 'redirect';
	$replace = $to['type'] === 'replace';

	$arTo = explode('/', $to['path']);
	$arFrom = explode('/', $from);

	if(count($arTo) !== 2){
		die('There is a problem with the routes, they either don\'t have view or controller');
	}

	// if the current page is the root and __ROOT__ has been defined in routes configuration
	if($from === '__ROOT__' && !isset( $_GET['argv'] )){
		$controller = $arTo[0];
		$method = "view_{$arTo[1]}";
		break;
	}
	else if ( isset( $_GET['argv'] ) && !empty( $_GET['argv'] ) ) {
		// split the arguments into an array
		$argv = explode( '/', $_GET['argv'] );
		// number of arguments
		$argc = count( $argv );

		// if one or more arguments set the first to controller
		if($argc > 0 && !empty( $argv[0] )){
			$controller = $argv[0];
		}

		// if two or more arguments set the second to method
		$method = 'view_index';
		if($argc > 1 && !empty( $argv[1] )){
			$method = "view_{$argv[1]}";
		}

		// if three or more arguments set the rest to params
		$params = ( $argc < 1 ) ?: array_slice( $argv, 2 );

		if((isset($arFrom[0]) && $controller == $arFrom[0]) && (isset($arFrom[1]) && $method == "view_{$arFrom[1]}")){
			$controller = $arTo[0];
			$method = "view_{$arTo[1]}";
			break;
		}
		// if used replace then redirect
		if($replace && (isset($arTo[0]) && $controller == $arTo[0]) && (isset($arTo[1]) && $method == "view_{$arTo[1]}")){
			header("HTTP/1.1 301 Moved Permanently");
			header("location:/{$arFrom[0]}/$arFrom[1]");
			exit();
		}
	}
}


if($controller !== NULL && $method !== NULL)

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
			echo 'The file exists but not the class you may have misconfigured your controller';
		}
	}
	catch( \Exception $e ) {
		echo $e->getMessage();
	}



