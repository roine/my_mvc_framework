<?php
class Loader {
	public static function register() {
		spl_autoload_register( 'self::load', true, true );
	}

	private static function load( $class ) {
		$class = ltrim( strtolower( $class ), '\\' );
		$fileName = '';
		$namespace = '';
		// load core files
		require_once CORE_ROOT.'/base.php';
		require_once CORE_ROOT.'/orm.php';
		require_once CORE_ROOT.'/config.php';
		require_once CORE_ROOT.'/utils.php';
		require_once CORE_ROOT.'/database.php';

		// explode the class, if more than 1 value then the first value is the path classes
		// should be in this format Model_User for model and User for controller
		$fileName = explode( '_', $class );

		// if the array has only one value then it's the controller, set the path to 'controller'
		$path = ( count( $fileName ) === 1 ) ? 'controller' : $fileName[0];

		// if it's a controller then the first value is the class name else it's the second
		$fileName = $path === 'controller' ? $fileName[0] : $fileName[1];

		// try loading the controller if file do not exists throw exception
		if ( !@include APP_ROOT.'/classes/'.Utils::pluralize( $path ).'/'.$fileName.'.php' ) {
			throw new \Exception( 'Failed to load '.APP_ROOT.'/'.Utils::pluralize( $path ).'/'.$fileName.'.php' );
		}

	}
}
