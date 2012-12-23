<?php

class Orm{

	protected static $tablename = '';
	private static $connection;

	public static function __callStatic($meth, $argv){

		// handle the magic find_by
		$arrMeth = explode('_', $meth);
		if($arrMeth[0] == 'find' && $arrMeth[1] == 'by'){
			try{
				self::find_by($arrMeth[2], $argv);	
			}
			catch(Exception $e){
				echo $e->getMessage();
				print_r($e->getTrace());
			}
			
		}

	}

	private function find_by($filter, $value){
		$connection = self::$connection;
		$tablename = self::$tablename;
		$params = array();

		// throw error if no arguments
		if(count($value) == 0)
			throw new Exception('no parameter into your find_by_'.$filter);
		else{
			// count the number of arguments
			$value = gettype($value[0]) === 'array' ? $value[0] : $value;
			// prepare the query
			$cond = array_fill(0, count($value), ' ? = ? ');
			$sCond = implode(' OR ',$cond);

			// populate with tablename first
			// $params[] = $tablename;
			// populate with the filters
			foreach($value as $v){
				$params[] = $filter;
				$params[] = $v;
			}
			$sql = "SELECT * FROM $tablename WHERE ( {$sCond} )";
			$result = $connection->prepare($sql)->execute($params)->fetch();

			// $connection;
		}
		

	}

	public static function find_all(){
		$connection = self::$connection;
		$tablename = self::$tablename;
		$existsTable = self::existsTable($tablename, $connection);

		if($existsTable && $connection){
			return $connection->query('SELECT * FROM home')->fetchAll();
		}
	}

	private function existsTable($tablename, $connection){
		try{
			$query = $connection->query("SELECT 1 FROM $tablename");
			$query->execute();		
		}
		catch(PDOException $e){
			// if exception is thrown then the table doesnt exists
			return false;
		}		
		return true;
	}
	
	public static function init($tablename = null){


		if(gettype($tablename) == 'string' && !empty($tablename)){
			// if table defined then set the table name
			self::$tablename = $tablename;
		}
		else{
			// ...else get the table name from the model name
			self::$tablename = $tablename = implode(',', self::$tablename = array_slice(explode('_', get_called_class()), 1));
		}

		// PDO init
		$ini = ROOT.'/config/database/config.ini' ;
		$config = parse_ini_file ( $ini , true );
		if (class_exists('PDO')){
			try{
				self::$connection = new PDO($config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['database'], $config['user'], $config['password']);
				// PDOException on
				self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
		}
		else{
			echo 'PDO is not enabled on your server';
		}
		
		return new self;
	}
}