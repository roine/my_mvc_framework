<?php
class Home extends Base{

	public function view_index($params){
		// print_r(Model_Users::create(array('username' => 'jonathan', 'email' => 'jonathan@ikonfx.com')));
		print_r(Model_users::find_all());
		Model_users::update(6, array('email' => 'sophie@joso.com', 'username' => 'so'));

		$this->view('home/index');

		if(count($params) > 0)
			echo 'hello '.implode(',',$params);
	}


}