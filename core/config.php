<?php

class Config{

	private static $config = array();
	// static construtor
	public static function __callStatic($meth, $arg){
		// add the config file
		require_once(CONFIG_ROOT.DIRECTORY_SEPARATOR.'config.php');
		self::$config = $config;
		// add underscore to call the methods
		$meth = '_'.$meth;
		
		if(method_exists(__CLASS__, $meth))
			return self::$meth(implode('.', $arg));
		else
			throw new Exception('The method '.$meth.' doesn\'t exists');
	}


	public static function _load($params = ''){
		
		if(empty($params)){
			throw new Exception('No params have been set');
		}
		else{
			$conf = explode('.', $params);
			$temp = self::$config;
			foreach($conf as $key) {
			   $temp = $temp[$key];
			}
			return $temp;

			
		}
	}

	
}