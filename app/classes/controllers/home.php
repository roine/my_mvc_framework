<?php
class Home extends Base{

	public function view_index($params){
		echo 'homepage';
		$blocks = Model_home::init()->find_all();
		Model_home::find_by_id(1,2);
		// print_r($blocks);
		if(count($params) > 1)
			echo 'hello '.implode(',',$params);
	}


}