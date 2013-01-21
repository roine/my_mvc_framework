<?php

class UtilsTest extends PHPUnit_Framework_TestCase{

	protected $object;

	protected function setUp()
    {
        defined('ROOT') or define('ROOT', dirname(__DIR__).'/../');
        include_once(dirname(__DIR__).DIRECTORY_SEPARATOR.'utils.php');
        $this->object = new Utils;
    }

    protected function tearDown()
    {
    }

    public function testPluralize(){
    	$this->assertEquals($this->object->pluralize('repository'), 'repositories');
    	$this->assertEquals($this->object->pluralize('user'), 'users');
    	$this->assertEquals($this->object->pluralize('test'), 'tests');
    }

    public function testPluralize_if(){
        $this->assertEquals($this->object->pluralize_if('repository', 3), '3 repositories');
        $this->assertEquals($this->object->pluralize_if('repository', 1), '1 repository');
    }

}