<?php

class Orm {

	protected static $tablename = '';
	private static $connection;

	public static function __callStatic( $meth, $argv ) {

		// handle the magic find_by
		$arrMeth = explode( '_', $meth );

		// init PDO if not
		if ( gettype( self::$connection ) !== 'object' ) {
			self::$connection = Db::init();
		}

		// get the tablename from the model name, ie: Model_Users => users
		self::$tablename = strtolower( implode( ',', array_slice( explode( '_', get_called_class() ), 1 ) ) );

		if ( $arrMeth[0] == 'find' && $arrMeth[1] == 'by' ) {
			if ( isset( $arrMeth[2] ) && !empty( $arrMeth[2] ) ) {
				try{
					return self::_find_by( $arrMeth[2], $argv );
				}
				catch( Exception $e ) {
					echo $e->getMessage();
					print_r( $e->getTrace() );
				}
			}
			else if ( !isset( $arrMeth[2] ) || empty( $arrMeth[2] ) ) {
					throw new Exception( 'The method find_by doesn\'t exists' );
				}
		}

		return 'Something went wrong while using '.$meth;
	}

	private static function _find_by( $filter, $value ) {

		$connection = self::$connection;
		$tablename = self::$tablename;
		if(!Db::isUniqueField($tablename, $filter, $connection))
			throw new Exception('it is not unique');
		// if double array an array has been use as argument, extract it
		$value = ( gettype( $value[0] ) === 'array' ) ? $value[0] : $value;
		// check whether the table exists
		$existsTable = self::existsTable( $tablename, $connection );

		// throw error if no arguments
		if ( count( $value ) == 0 ) {
			throw new Exception( 'no parameter into your find_by_'.$filter );
		}
		else {
			$value = ( gettype( $value ) === 'string' ) ? explode( ',', $value ) : $value;

			// prepare the query to look like: filter = ? OR filter = ? ...
			$cond = implode( ' OR ', array_fill( 0, count( $value ), " ($filter = ?) " ) );
			$sql = "SELECT * FROM $tablename WHERE {$cond};";
			try{
				if ( $existsTable && $connection ) {

					$result = $connection->prepare( $sql );
					$result->execute( $value );

					return $result->fetchAll( \PDO::FETCH_ASSOC );
				}
				else {

				}
			}
			catch( Exception $e ) {
				die( $e->getMessage() );
			}
		}
	}


	public static function find_all() {
		$connection = self::$connection;
		$tablename = self::$tablename;
		$existsTable = self::existsTable( $tablename, $connection );

		if ( $existsTable && $connection ) {
			return $connection->query( 'SELECT * FROM home' )->fetchAll( \PDO::FETCH_ASSOC );
		}
	}


	public static function init( $tablename = null ) {

		if ( !self::existsTable( $tablename, self::$connection ) )
			self::$tablename = Utils::pluralize( $tablename );
		return new self;
	}


}
