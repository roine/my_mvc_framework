<?php
class FindByTest extends PHPUnit_Framework_TestCase
{
    public function testerPushEtPop()
    {
        $this->assertEquals(Orm::init()->find_by('id', 1, 'home'));
    }
}
?>