<?php
class Home extends Base{

	public function view_index($params){
		echo 'homepage';
		$blocks = Model_home::init()->find_all();
		print_r(Model_home::find_by_id(1,2));
		// var_dump($blocks);
		if(count($params) > 1)
			echo 'hello '.implode(',',$params);
	}


}