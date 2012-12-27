<?php
class Home extends Base{

	public function view_index($params){
		echo 'homepage';
		// $blocks = Model_home::find_all();
		// print_r(Model_Home::find_by_id(1,2));
		
		print_r(Model_User::find_by_id(2));

		// var_dump($blocks);
		if(count($params) > 1)
			echo 'hello '.implode(',',$params);
	}


}