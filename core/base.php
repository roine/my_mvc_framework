<?php
// base controller
class Base {

	/**
	 * name of the active template
	 *
	 * @var string
	 *
	 * @access protected
	 */
	protected  $template = 'default';


	/**
	 * list of active css
	 *
	 * @var array
	 *
	 * @access private
	 */
	private $css = array();


	/**
	 * list of active js
	 *
	 * @var array
	 *
	 * @access private
	 */
	private $js = array();


	/**
	 * content to add into the template, contain the view
	 *
	 * @var string
	 *
	 * @access public
	 */
	public $content = '';



    /**
     * load the views
     *
     * @param string $view name of the view.
     * @param array $data data to send to the view.
     *
     * @access public
     *
     * @return mixed Value.
     */
	public function view( $view = '', $data = array() ) {

		$path = APP_ROOT.'/views/'.$view.'.php';

		if ( $view === '' )
			throw new \Exception( 'no view defined' );


		if(gettype( $data ) !== 'array')
			throw new \Exception( 'Parameter must be an array' );

		if ( file_exists( $path ) ) {
			$this->content =  $this->getRequire( $path, $data );
			// $this->content =  file_get_contents($path);

		}
		else {
			throw new \Exception( 'Failed to load '.$path );
		}
		$content = $this->content;


		require APP_ROOT."/views/templates/".$this->template.".php";

	}



    /**
     * use ob to buffer the output of the file
     *
     * @param string $filename	name of the file.
     * @param array $data     	data to send to the file.
     *
     * @access private
     *
     * @return string Value.
     */
	private function getRequire( $filename = '', $data = array() ) {
		ob_start();
		extract( $data );
		require $filename;

		return ob_get_clean();
	}


}
