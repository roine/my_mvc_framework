<?php
class Users extends Base{

	public function view_index()
	{
		$data['users'] = Model_Users::find_all();
		$this->view('users/index', $data);

	}
	public function view_view($username)
	{
		$data = array();
		try{
			$data['user'] = Model_Users::find_by_username($username);
		}
		catch(Exception $e){
			$e->getMessage();
		}
		$this->view('users/view', $data);
	}
}