<?php
class Db{
	public static $connection;

	public static function init(){
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
		return self::$connection;
	}

    /**
     * check whether a table exists
     *
     * @param String $tablename  name of the table.
     * @param Object $connection PDO object.
     *
     * @access private
     * @static
     *
     * @return boolean Value.
     */
	private static function existsTable( $tablename, $connection )
	{
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

    /**
     * check whether a column has been set a UNIQUE
     *
     * @param string $tablename  table name.
     * @param string $field      name of the column.
     * @param PDO object $connection PDO object to establish the connection.
     *
     * @access public
     * @static
     *
     * @return boolean Value.
     */
	public static function isUniqueField($tablename, $field, $connection)
	{
		$query = $connection->query("SHOW INDEXES FROM $tablename WHERE Column_name='$field' AND Non_unique");
		$query->execute();
		if(!$query->fetchAll()){
			return true;
		}
		return false;
	}

	public static function existsRow($tablename, $connection, $data = array())
	{

	}

}