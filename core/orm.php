<?php

class Orm {


    /**
     * The name of the table in the database
     *
     * @var string
     *
     * @access protected
     * @static
     */
    public static $tablename = '';



    /**
     * The pdo connection to be reused
     *
     * @var PDO Object
     *
     * @access private
     * @static
     */
    public static $connection;



    /**
     * Set the class name which inherit the Orm class and transform it into a table name
	 *
	 * `Model_Users` will become `users`
	 *
	 * TODO: later the table name could be defined statically, modify this method to make it works
	 *
     * @access private
     * @static
     *
     * @return mixed Value.
     */
    private static function setTableName() {
        if(static::$tablename == '')
          static::$tablename = strtolower( implode( ',', array_slice( explode( '_', get_called_class() ), 1 ) ) );
  }

    /**
     * get the table if exists if not call setTableName
     *
     * @access private
     * @static
     *
     * @return string Value.
     */
    public static function getTableName(){
        // if tablename is not defined then we define it
        if(static::$tablename == ''){
            static::setTableName();
        }
        return static::$tablename;
    }

    /**
     * set the PDO connection if not already set
     *
     * @access private
     * @static
     *
     * @return mixed Value.
     */
    private static function setConnection() {
        // if the connection is not already set then create it
      if ( gettype( static::$connection ) !== 'object' ) {
         static::$connection = Db::init();
     }
 }

    /**
     * get the connection, if not call setConnection
     *
     * @access private
     * @static
     *
     * @return mixed Value.
     */
    public static function getConnection(){
        if(!static::$connection){
            static::setConnection();
        }
        return static::$connection;
    }

    /**
     * __callStatic
     *
     * @param string $meth method name.
     * @param array $argv arguments values.
     *
     * @access public
     * @static
     *
     * @return mixed Value.
     */
    public static function __callStatic( $meth, $argv ) {
		// separate each argument from the method
    	$arrMeth = explode( '_', $meth );

		// init PDO if not
    	static::setConnection();

		// get the tablename from the model name, ie: Model_Users => users
    	static::setTableName();


         // handles the find_by magic method
    	if ( $arrMeth[0] == 'find' && $arrMeth[1] == 'by' ) {
    		if ( isset( $arrMeth[2] ) && !empty( $arrMeth[2] ) ) {
    			try{
    				return static::_find_by( $arrMeth[2], $argv );
    			}
    			catch( Exception $e ) {
    				echo $e->getMessage();
    			}
    		}
    		else if ( !isset( $arrMeth[2] ) || empty( $arrMeth[2] ) ) {
    			throw new \Exception( 'The method find_by doesn\'t exists' );
    		}
    	}

        // handles the find_first and find_last magic methods and set a second param
        // ASC or DESC, default being DESC
    	else if($arrMeth[0] == 'find' && ($arrMeth[1] == 'first' || $arrMeth[1] == 'last')){
    		$order = $arrMeth[1] == 'first' ? "DESC" : 'ASC';
    		return static::_find_edge($argv, $order);
    	}

        // if no method has been found then return a message
    	return 'Something went wrong while using '.$meth;
    }

    /**
     * find rows by... from the current table ie: Model_Users:find_by_id(3)
     *
     * @param string $filter name of column to filter.
     * @param string $value  value to filter.
     *
     * @access private
     * @static
     *
     * @return associative array Value.
     */
    private static function _find_by( $filter = '', $value = '') {

		// get the connection, if it not in PDO then exit
    	$connection = static::$connection;

    	$tablename = static::$tablename;

    	if ( !Db::isUniqueField( $tablename, $filter, $connection ) )
    		throw new \Exception( 'it is not field type UNIQUE so you cannot search by '.$filter );
		// if double array an array has been use as argument, extract it
    	$value = ( gettype( $value[0] ) === 'array' ) ? $value[0] : $value;
		// check whether the table exists
    	$existsTable = static::existsTable( $tablename, $connection );

		// throw error if no arguments
    	if ( count( $value ) == 0 ) {
    		throw new \Exception( 'no parameter into your find_by_'.$filter );
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
    		catch( \Exception $e ) {
    			die( $e->getMessage() );
    		}
    	}
    }


    /**
     * find all the rows from the current table, ie: Model_Users::find_all()
     *
     * @access public
     * @static
     *
     * @return associative array Value.
     */
    public static function find_all() {
    	$connection = static::getConnection();
    	$tablename = static::getTableName();
    	$existsTable = static::existsTable( $tablename, $connection );

    	if ( $existsTable && $connection ) {
    		return $connection->query( "SELECT * FROM {$tablename}" )->fetchAll( \PDO::FETCH_ASSOC );
    	}
    }



    /**
     * find all the first or last data from current table, ie: Model_Users::find_first(3), Model_users::find_last(2)
     *
     * @param int  $limit Number of rows to output.
     * @param string $order Order to output, DESC or ASC.
     *
     * @access private
     * @static
     *
     * @return associative array Value.
     */
    private static function _find_edge($limit = 0, $order = "DESC"){
    	$connection = static::getConnection();
    	$tablename = static::getTableName();
    	$existsTable = static::existsTable( $tablename, $connection );
    	$limit = count($limit) < 1 ? 1 : $limit[0];

    	if($existsTable && $connection){
    		return $connection->query("SELECT * FROM {$tablename} ORDER BY id {$order} LIMIT {$limit}")->fetchAll( \PDO::FETCH_ASSOC );
    	}

    }



    /**
     * delete a row from current table, ie: Model_Users::delete(1)
     *
     * @param int $id Unique ID.
     *
     * @access public
     * @static
     *
     * @return mixed Value.
     */
    public static function delete( $id = null ) {
    	$connection = static::getConnection();
    	$tablename = static::getTableName();
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



    /**
     * create a new row,
     * ie:$user = array('username' => 'jon' 'email' => 'jon@jon.com'); Model_Users::create($user)
     *
     * @param associative array $data contains the column name and the related value.
     *
     * TODO: return the id
     *
     * @access public
     * @static
     *
     * @return mixed Value.
     */
    public static function create( $data = array() ) {
    	$connection = static::getConnection();
    	$tablename = static::getTableName();

    	if ( gettype( $data ) !== 'array' ) {
    		throw new \Exception( 'parameter must be an array' );
    	}
    	else if ( count( $data ) == 0 ) {
    		throw new Exception( 'no paramaters or empty array' );
    	}
    	else {
    		$fields = implode( ',', array_keys( $data ) );
    		$values = array_values( $data );
    		$params = implode( ', ', array_fill( 0, count( $data ), '?' ) );
            try{
              $query = $connection->prepare( "INSERT INTO {$tablename} ($fields) VALUES ($params)" );
              $query->execute( $values );
          }
          catch( \PDOException $e ) {
            echo $e->getMessage();
        }
    }
}



    /**
     * update a row
     *
     * @param int $id   Unique ID.
     * @param associative array $data contains the column name and the related value.
     *
     * @access public
     * @static
     *
     * @return mixed Value.
     */
    public static function update( $id = 0, $data = array() ) {
    	$connection = static::getConnection();
    	$tablename = static::getTableName();
    	$id = (int)$id;
    	$columns = array_keys( $data );
    	$values = array_values( $data );
    	$prepare = implode( '=?, ', $columns ).'=?';

    	$sql = "UPDATE {$tablename} SET {$prepare} WHERE id={$id}";
    	try{
    		$query = $connection->prepare( $sql );
    		$query->bindParam( ":id", $id );
    		$query->execute( $values );
    	}
    	catch( \PDOException $e ) {
    		echo $e->getMessage();
    	}
    }


}
