<?php

class Orm {

	protected static $tablename = '';
	private static $connection;

	public static function __callStatic( $meth, $argv ) {
		// handle the magic find_by
		$arrMeth = explode( '_', $meth );
		self::init();
		if ( $arrMeth[0] == 'find' && $arrMeth[1] == 'by' ) {
			try{

				return self::_find_by( $arrMeth[2], $argv );
			}
			catch( Exception $e ) {
				echo $e->getMessage();
				print_r( $e->getTrace() );
			}

		}
		return 'method '.$meth.' do not exists';

	}

	private static function _find_by( $filter, $value, $tablename = null ) {

		$connection = self::$connection;
		$tablename = $tablename ?: self::$tablename;
		$params = array();
		$existsTable = self::existsTable( $tablename, $connection );
		// throw error if no arguments
		if ( count( $value ) == 0 )
			throw new Exception( 'no parameter into your find_by_'.$filter );
		else {

			$value = gettype( $value[0] ) === 'array' ? $value[0] : $value;

			// prepare the query to look like: filter = ? OR filter = ? ...
			$cond = implode( ' OR ', array_fill( 0, count( $value ), " ($filter = ?) " ) );
			$sql = "SELECT * FROM $tablename WHERE {$cond};";
			try{
				if ( $existsTable && $connection ) {

					$result = $connection->prepare( $sql );
					$result->execute( $value );

					return $result->fetchAll( \PDO::FETCH_ASSOC );
				}
				else{
		echo 'df';

				}
			}
			catch( Exception $e ) {
				die( $e->getMessage() );
			}
		}
	}

	private static function _find_all() {
		$connection = self::$connection;
		$tablename = self::$tablename;
		$existsTable = self::existsTable( $tablename, $connection );

		if ( $existsTable && $connection ) {
			return $connection->query( 'SELECT * FROM home' )->fetchAll( \PDO::FETCH_ASSOC );
		}
	}


	private static function existsTable( $tablename, $connection ) {
		try{
			$query = $connection->query( "SELECT 1 FROM $tablename" );
			$query->execute();
		}
		catch( PDOException $e ) {
			// if exception is thrown then the table doesnt exists
			return false;
		}
		return true;
	}

	public static function init( $tablename = null ) {

		if($tablename == null){
			// ...else get the table name from the model name
			self::$tablename = $tablename = implode( ',', self::$tablename = array_slice( explode( '_', get_called_class() ), 1 ) );	
		}
		$tablename = strtolower(self::$tablename);
		
		// PDO init
		$ini = ROOT.'/config/database/config.ini' ;
		$config = parse_ini_file( $ini , true );
		if ( class_exists( 'PDO' ) ) {
			try{
				self::$connection = new \PDO( $config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['database'], $config['user'], $config['password'], array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' ) );
				// PDOException on
				self::$connection->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
			}
			catch( PDOException $e ) {
				die( $e->getMessage() );
			}

		}
		else {
			echo 'PDO is not enabled on your server';
		}
		if(!self::existsTable($tablename, self::$connection))
			self::$tablename = self::pluralize($tablename);
		return new self;
	}

	public static function pluralize($str){
		$plural = array(
            array( '/(quiz)$/i',               "$1zes"   ),
	        array( '/^(ox)$/i',                "$1en"    ),
	        array( '/([m|l])ouse$/i',          "$1ice"   ),
	        array( '/(matr|vert|ind)ix|ex$/i', "$1ices"  ),
	        array( '/(x|ch|ss|sh)$/i',         "$1es"    ),
	        array( '/([^aeiouy]|qu)y$/i',      "$1ies"   ),
	        array( '/([^aeiouy]|qu)ies$/i',    "$1y"     ),
            array( '/(hive)$/i',               "$1s"     ),
            array( '/(?:([^f])fe|([lr])f)$/i', "$1$2ves" ),
            array( '/sis$/i',                  "ses"     ),
            array( '/([ti])um$/i',             "$1a"     ),
            array( '/(buffal|tomat)o$/i',      "$1oes"   ),
            array( '/(bu)s$/i',                "$1ses"   ),
            array( '/(alias|status)$/i',       "$1es"    ),
            array( '/(octop|vir)us$/i',        "$1i"     ),
            array( '/(ax|test)is$/i',          "$1es"    ),
            array( '/s$/i',                    "s"       ),
            array( '/$/',                      "s"       )
        );

		foreach ( $plural as $pattern ){
        if ( preg_match( $pattern[0], $str ) )
            return preg_replace( $pattern[0], $pattern[1], $str );
        }
	}
}
