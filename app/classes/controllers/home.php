<?php
class Home extends Base{

	public function view_index($params){
		echo 'homepage';
		$blocks = Model_home::init()->find_all();
		print_r($blocks);
		if(count($params) > 1)
			echo 'hello '.implode(',',$params);
	}


}