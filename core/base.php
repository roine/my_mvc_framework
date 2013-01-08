<?php
// base controller
class Base {

	protected  $template = 'default';
	private $css = array();
	private $js = array();
	public $content = '';

	public function view( $view = NULL, $data = '' ) {

		$path = APP_ROOT.'/views/'.$view.'.php';

		if ( $view == NULL )
			throw new \Exception( 'no view defined' );

		
		if ( gettype( $data ) == 'array' && count( $data ) < 1 )
			throw new \Exception( 'Parameters empty' );

		if(file_exists($path)){
			$this->content =  $this->getRequire($path, $data);
			// $this->content =  file_get_contents($path);
		}
		else{
			throw new \Exception( 'Failed to load '.$path );
		}
		$content = $this->content;
		
		
		require(APP_ROOT."/views/templates/".$this->template.".php");

	}

	private function getRequire($filename, $data) {
		ob_start();
		extract($data);
		require($filename);

		return ob_get_clean();
		}


}
