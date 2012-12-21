<?php
class Home extends Base{

	public function view_index($params){
		echo 'homepage';
		Model_home::init('prout')->find_all();
		if(count($params) > 1)
			echo 'hello '.implode(',',$params);
	}


}