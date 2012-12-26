<?php
	define('ROOT', dirname(__DIR__));
	define('CORE_ROOT', ROOT.DIRECTORY_SEPARATOR.'core');
	define('APP_ROOT', ROOT.DIRECTORY_SEPARATOR.'app');
	define('CONFIG_ROOT', ROOT.DIRECTORY_SEPARATOR.'config');
	// check whether mod_rewrite is actiivated
	if (function_exists('apache_get_modules')) {
	  	$modules = apache_get_modules();
	  	$mod_rewrite = in_array('mod_rewrite', $modules);
	} else {
	  	$mod_rewrite =  getenv('HTTP_MOD_REWRITE')=='On' ? true : false ;
	}
	if(!$mod_rewrite){
		exit('mod rewrite off!');
	}
	
	require('../core/bootstrap.php');
?>