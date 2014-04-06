<?php
class TestKeyboardPathFinder extends PHPUnit_Framework_TestCase{

    public function testKeyToKeyPath() {
        // No movement cases
        $aPath = KeyboardPathFinder::getKeyToKeyPath("A", "A");
        $this->assertEquals(count($aPath), 0);
        $aPath = KeyboardPathFinder::getKeyToKeyPath(ScreenKeyboard::SPACEBAR, " ");
        $this->assertEquals(count($aPath), 0);
        $aPath = KeyboardPathFinder::getKeyToKeyPath(ScreenKeyboard::BACKSPACE, "\010");
        $this->assertEquals(count($aPath), 0);

        // Just arround starting key
        $aPath = KeyboardPathFinder::getKeyToKeyPath("A", "B");
        $this->assertEquals(count($aPath), 1);
        $this->assertEquals($aPath[0], KeyboardPathFinder::RIGHT);
        $aPath = KeyboardPathFinder::getKeyToKeyPath("A", "Z");
        $this->assertEquals(count($aPath), 1);
        $this->assertEquals($aPath[0], KeyboardPathFinder::LEFT);
        $aPath = KeyboardPathFinder::getKeyToKeyPath("A", "`");
        $this->assertEquals(count($aPath), 1);
        $this->assertEquals($aPath[0], KeyboardPathFinder::UP);
        $aPath = KeyboardPathFinder::getKeyToKeyPath("A", "a");
        $this->assertEquals(count($aPath), 1);
        $this->assertEquals($aPath[0], KeyboardPathFinder::DOWN);

        // More then 1 step
        $aPath = KeyboardPathFinder::getKeyToKeyPath("A", "D");
        $this->assertEquals(count($aPath), 3);
        $this->assertEquals($aPath, array(KeyboardPathFinder::RIGHT, KeyboardPathFinder::RIGHT, KeyboardPathFinder::RIGHT));
        $aPath = KeyboardPathFinder::getKeyToKeyPath("A", "Y");
        $this->assertEquals(count($aPath), 2);
        $this->assertEquals($aPath, array(KeyboardPathFinder::LEFT, KeyboardPathFinder::LEFT));
        $aPath = KeyboardPathFinder::getKeyToKeyPath("A", "b");
        $this->assertNotNull($aPath);
        $this->assertEquals(count($aPath), 2);
        $this->assertEquals($aPath, array(KeyboardPathFinder::DOWN, KeyboardPathFinder::RIGHT));
        // Special keys
        $aPath = KeyboardPathFinder::getKeyToKeyPath("\010", "B");
        $this->assertNotNull($aPath);
        $this->assertEquals(count($aPath), 3);
        $this->assertEquals($aPath, array(KeyboardPathFinder::DOWN, KeyboardPathFinder::RIGHT, KeyboardPathFinder::RIGHT));
        $aPath = KeyboardPathFinder::getKeyToKeyPath(" ", "#");
        $this->assertEquals($aPath, array(KeyboardPathFinder::UP));
        $aPath = KeyboardPathFinder::getKeyToKeyPath(" ", "L");
        $this->assertEquals(count($aPath), 4);
        $this->assertEquals($aPath, array(KeyboardPathFinder::DOWN, KeyboardPathFinder::RIGHT, KeyboardPathFinder::RIGHT, KeyboardPathFinder::RIGHT));
    }


    public function testFindOptimumPath() {
        $this->fail("Optiomum path of string haven't impletemented");
    }
}
?>