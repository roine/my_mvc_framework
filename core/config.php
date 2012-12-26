<?php

class Config{

	private static $config = [];
	// static construtor
	public static function __callStatic($meth, $arg){
		// add the config file
		require_once(CONFIG_ROOT.DIRECTORY_SEPARATOR.'config.php');
		self::$config = $config;
		// add underscore to call the methods
		$meth = '_'.$meth;
		
		if(method_exists(__CLASS__, $meth))
			self::$meth(implode('.', $arg));
		else
			throw new Exception('The method '.$meth.' doesn\'t exists');
	}


	public static function _load($params = ''){
		
		if(empty($params)){
			throw new Exception('No params have been set');
		}
		else{
			$conf = explode('.', $params);
			echo '<pre>';
			print_r($conf);
			echo '<pre>';
			print_r(self::$config);
			$str = [];
			foreach($conf as $k => $v){
				$config[$k];

			}

			
		}
	}

	
}