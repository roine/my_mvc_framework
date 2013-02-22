<?php

class Routes{
    /**
     * $routes
     *
     * @var array
     *
     * @access public
     * @static
     */
    public static $routes = array();

    /**
     * $from
     *
     * @var string
     *
     * @access private
     * @static
     */
    private static $from = '';

    public static function set($paths = array()){
    	foreach($paths as $base => $redirect){
    		self::$routes[$base]['type'] = 'redirect';

    		if(gettype($redirect) === 'string'){
    			self::$routes[$base]['path'] = $redirect;
    		}
	    	else{
	    		if(isset($redirect['path'])){
	    			self::$routes[$base]['path'] = $redirect['path'];
	    		}
	    		else{
	    			throw new Exception('You didn\'t define a path to redirect in your routes');
	    		}
	    		if(isset($redirect['type'])){
	    			self::$routes[$base]['type'] = $redirect['type'];
	    		}
	    	}
    	}
    }

    /**
     * when
     * 
     * @param mixed $path Description.
     *
     * @access public
     * @static
     *
     * @return mixed Value.
     */
    public static function when($path){
    	self::$from = $path;
    	return new self;

    }

    /**
     * then
     * 
     * @param mixed $path Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function then($path){
    	self::$routes[self::$from]['path'] = $path;
    	self::$routes[self::$from]['type'] = 'redirect';
    	return $this;
    }

    /**
     * replace
     * 
     * @param mixed $path Description.
     *
     * @access public
     *
     * @return mixed Value.
     */
    public function replace($path){
    	self::$routes[self::$from]['path'] = $path;
    	self::$routes[self::$from]['type'] = 'replace';
    	return $this;
    }

}