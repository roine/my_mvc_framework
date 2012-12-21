<?php

class Orm{

	protected static $tablename = '';
	private static $connection;


	public static function find_all(){
		$connection = self::$connection;
		$tablename = self::$tablename;
		if($connection != null){
			$query = $connection->query("SELECT * FROM $tablename")->execute();
			if($query)
				$query->fetch();
		}
			
		
		
		
	}
	
	public static function init($tablename = null){

		if(gettype($tablename) == 'string' && !empty($tablename)){
			// if table defined then set the table name
			self::$tablename = $tablename;
		}
		else{
			// ...else get the table name from the model name
			self::$tablename = implode(',', self::$tablename = array_slice(explode('_', get_called_class()), 1));
		}
		// PDO init
		$ini = ROOT.'/config/database/config.ini' ;
		$config = parse_ini_file ( $ini , true );
		if (class_exists('PDO')){
			try{
				self::$connection = new PDO($config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['database'], $config['user'], $config['password']);
			}
			catch(Exception $e){
				echo $e->getMessage();
			}
			
		}
		else{
			echo 'PDO is not enabled on your server';
		}
		
		return new self;
	}
}