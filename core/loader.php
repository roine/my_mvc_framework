<?php
class Loader{
	public static function register(){
		spl_autoload_register('self::load', true, true);
	}

	private static function load( $class ) {
		$class = strtolower( $class );

		// load core files
		require_once CORE_ROOT.'/base.php';
		require_once CORE_ROOT.'/orm.php';
		require_once CORE_ROOT.'/config.php';
		require_once CORE_ROOT.'/utils.php';
		require_once CORE_ROOT.'/database.php';

		// explode the class, if more than 1 value then the first value is the path classes
		// should be in this format Model_User for model and User for controller
		$class = explode( '_', $class );

		// if the array has only one value then it's the controller, set the path to 'controller'
		$path = ( count( $class ) === 1 ) ? 'controller' : $class[0];

		// if it's a controller then the first value is the class name else it's the second
		$class = $path === 'controller' ? $class[0] : $class[1];

		// try loading the controller if file do not exists throw exception
		if ( !@include APP_ROOT.'/classes/'.Utils::pluralize( $path ).'/'.$class.'.php' ) {
			throw new \Exception( 'Failed to load '.APP_ROOT.'/'.Utils::pluralize( $path ).'/'.$class.'.php' );
		}

	}
}