<?php

class Orm {

	protected static $tablename = '';
	private static $connection;

	private static function setTableName() {
		return self::$tablename = strtolower( implode( ',', array_slice( explode( '_', get_called_class() ), 1 ) ) );
	}

	private static function setConnection() {
		if ( gettype( self::$connection ) !== 'object' ) {
			return self::$connection = Db::init();
		}
	}

	public static function __callStatic( $meth, $argv ) {

		// handle the customs magic methods
		$arrMeth = explode( '_', $meth );

		// init PDO if not
		self::setConnection();


		// get the tablename from the model name, ie: Model_Users => users
		self::setTableName();

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

		// get the connection, if it not in PDO then exit
		$connection = is_a( self::$connection, 'PDO' ) ? self::$connection : exit( 'No database connection' );

		$tablename = self::$tablename;

		if ( !Db::isUniqueField( $tablename, $filter, $connection ) )
			throw new Exception( 'it is not field type UNIQUE so you cannot search by '.$filter );
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
			$cond = implode( ',', array_fill( 0, count( $value ), "?" ) );
			$sql = "SELECT * FROM $tablename WHERE {$filter} IN({$cond});";
			try{
				if ( $existsTable ) {

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
		$connection = self::$connection ? self::$connection : self::setConnection();
		$tablename = self::setTableName();
		$existsTable = self::existsTable( $tablename, $connection );
		if ( $existsTable && $connection ) {
			return $connection->query( "SELECT * FROM {$tablename}" )->fetchAll( \PDO::FETCH_ASSOC );
		}
	}

	public static function delete( $id = null ) {
		$connection = self::$connection ? self::$connection : self::setConnection();
		$tablename = self::setTableName();
		$id = (int)$id;

		if ( $id == null )
			throw new Exception( 'id cannot be empty' );
		else if ( gettype( $id ) !== 'integer' )
				throw new Exception( 'id must be an integer' );
			else
				$query = $connection->prepare( "DELETE FROM {$tablename} WHERE id=:id " );
		$query->bindValue( ':id', $id );
		return $query->execute();
	}

	public static function create( $data = array() ) {
		$connection = self::$connection ? self::$connection : self::setConnection();
		$tablename = self::setTableName();

		if ( gettype( $data ) !== 'array' ) {
			throw new Exception( 'parameter must be an array' );
		}
		else if ( count( $data ) == 0 ) {
				throw new Exception( 'no paramaters or empty array' );
			}
		else {
			$fields = implode( ',', array_keys( $data ) );
			$values = array_values( $data );

			$params = implode( ', ', array_fill( 0, count( $data ), '?' ) );
			$query = $connection->prepare( "INSERT INTO {$tablename} ($fields) VALUES ($params)" );
			$query->execute( $values );
		}
	}

	public static function update($id, $data = array()){
		$connection = self::$connection ? self::$connection : self::setConnection();
		$tablename = self::setTableName();
		$id = (int)$id;
		$columns = array_keys($data);
		$values = array_values($data);
		$prepare = implode('=?, ', $columns).'=?';

		$sql = "UPDATE {$tablename} SET {$prepare} WHERE id={$id}";
		try{

			$query = $connection->prepare($sql);
			$query->bindParam(":id", $id);
			$query->execute($values);
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
		
	}

}
