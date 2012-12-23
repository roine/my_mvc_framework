<?php

if(isset($_GET['argv']) && !empty($_GET['argv'])){
	
	// set the vars to empty
	$controller = $method = '';
	$params = array();

	// autoload the current controller
	function __autoload($class) {
		$class = strtolower($class);

		// required by controllers and models
		require_once(CORE_ROOT.DIRECTORY_SEPARATOR.'base.php');
		require_once(CORE_ROOT.DIRECTORY_SEPARATOR.'models.php');

		// explode the class, if more than 1 value then the first value is the path
		$class = explode('_', $class);
		$path = (count($class) == 1) ? 'controller' : $class[0];
		$class = $path == 'controller' ? $class[0] : $class[1];
		// try loading the controller if file do not exists throw exception
	    if(!@include APP_ROOT.'/classes/'.$path.'s/'.$class.'.php'){
	    	throw new \Exception('Failed to load '.APP_ROOT.'/classes/controller/'.$class.'.php');
	    }

	}
	// split the arguments into an array
	$argv = explode('/',$_GET['argv']);

	// number of arguments
	$argc = count($argv);

	// if one or more arguments set the first to controller
 	$controller = ($argc < 0) ?: $argv[0];

 	// if two or more arguments set the second to method
 	$method = ($argc > 1 && !empty($argv[1])) ? 'view_'.$argv[1] : 'view_index';

 	// if three or more arguments set the rest to params
 	$params = ($argc < 2 ) ?: array_slice($argv, 2);

 	try{
		if(class_exists($controller)){
		 	$c = new $controller();
		 	if(method_exists($c, $method))
		 		$c->$method($params);
		 	else{
		 		echo 'The view <i>'.$method.'</i> doesnt exists';
		 	}
	 	}
	 	else{
	 		echo $controller;
	 		echo 'The file exists but not the class you may have misconfigured your controller';
		 }
 	}
 	catch(\Exception $e){
 		echo $e->getMessage();
 	}
 	

}