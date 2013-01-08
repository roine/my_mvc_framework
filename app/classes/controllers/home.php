<?php
class Home extends Base{

	public function view_index($params){
		// print_r(Model_Users::create(array('username' => 'jonathan', 'email' => 'jonathan@ikonfx.com')));
		// print_r(Model_users::find_all());
		// Model_users::update(6, array('email' => 'sophie@joso.com', 'username' => 'so'));
		$data['all'] = Model_users::find_all();
		$data['first'] = Model_users::find_first(1);
		$this->view('home/index', $data);

		if(count($params) > 0)
			echo 'hello '.implode(',',$params);
	}


}