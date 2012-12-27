<?php
class Users extends Base{

	public function view_index(){
		$users = Model_Users::init()->find_all();
		$this::render('users/index', 'sd');
		Config::load('paths.assets.js');
	}
}