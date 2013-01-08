<?php

class BaseTest extends PHPUnit_Framework_TestCase
{
	protected $object;

	protected function setUp(){
		defined('ROOT') or define('ROOT', dirname(__DIR__).'/../');
        include_once(dirname(__DIR__).DIRECTORY_SEPARATOR.'base.php');
        $this->object = new Base;
	}

	protected function tearDown(){

	}

}