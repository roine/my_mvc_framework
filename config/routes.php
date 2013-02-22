<?php
require_once CORE_ROOT."/routes.php";

	// Routes::when('__ROOT__')->then('home/index');
// Routes::when('users/index')->then('home/contact');

Routes::set(array(

	"__ROOT__" => "home/index",
	"users/index" => array("path" => "home/contact", "type" => "replace")

	));