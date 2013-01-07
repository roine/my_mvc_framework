<?php 
// base controller
class Base{

	protected static $template = 'default';
	private static $css = array();

	public static function __callStatic($meth, $arg){
		$meth = '_'.$meth;
		if(method_exists(__CLASS__, $meth))
			self::$meth(implode(',', $arg));
		else
			throw new Exception('The method '.$meth.' doesnt exists');
	}

	// render the data to a view
	private static function _render($view, $data = null){

	}

	



}