<?php
// base controller
class Base {

	protected  $template = 'default';
	private $css = array();
	private $js = array();

	public function view( $view = NULL, $data = '' ) {
		if ( $view == NULL )
			throw new \Exception( 'no view defined' );

		if ( gettype( $data ) == 'array' && count( $data ) < 1 )
			throw new \Exception( 'Parameters empty' );

		require(APP_ROOT.DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR.$this->template.".php");

	}


}
