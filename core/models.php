<?php

class Orm{

	protected static $tablename = '';
	private static $_instance;
	private static $config = [];

	
	private function __construct($tablename){
		if(gettype($tablename) == 'string' && !empty($tablename)){
			// if table defined then set the table name
			self::$tablename = $tablename;
		}
		else{
			// ...else get the table name from the model name
			self::$tablename = implode(',', self::$tablename = array_slice(explode('_', get_called_class()), 1));
		}
	}

	public static function find_all(){
		echo self::$tablename;
		
	}

	public static function init($tablename = null){

		// PDO init
		$ini = ROOT.'/config/database/config.ini' ;
		$config = parse_ini_file ( $ini , true );
		
		// Singleton
		if (!isset(self::$_instance)){
            self::$_instance = new self($tablename);
        }
        return self::$_instance;
	}
}