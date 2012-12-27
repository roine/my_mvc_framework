<?php
class Home extends Base{

	public function view_index($params){
		echo 'homepage';
		// $blocks = Model_home::find_all();
		// print_r(Model_Home::find_by_id(1,2));
		
		print_r(Model_Users::find_by_id(array(2,1)));

		// var_dump($blocks);
		if(count($params) > 1)
			echo 'hello '.implode(',',$params);
	}


}