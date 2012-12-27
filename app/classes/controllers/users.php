<?php
class Users extends Base{

	public function view_index(){
		$users = Model_Users::find_by_email('jonathan@ikonfx.com');
		print_r($users);
		$this::render('users/index', 'sd');
		echo Config::load('path.assets.js');
	}
	public function view_view($username){
		try{
			$users = Model_Users::find_by_username($username);
		}
		catch(Exception $e){
			$e->getMessage();
		}
		print_r($users);
	}
}