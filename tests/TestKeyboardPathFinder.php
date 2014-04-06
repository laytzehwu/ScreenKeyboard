<?php
class TestKeyboardPathFinder extends PHPUnit_Framework_TestCase{

    public function testKeyToKeyPath() {
        $aPath = KeyboardPathFinder::getKeyToKeyPath("A", "A");
        $this->assertEquals(count($aPath), 0);

        $aPath = KeyboardPathFinder::getKeyToKeyPath("A", "B");
        $this->assertEquals(count($aPath), 1);
    }


    public function testFindOptimumPath() {
        $this->fail("Optiomum path of string haven't impletemented");
    }
}
?>