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
    }

    public function testFindFunctionCalledWhenConstruct() {
        $classname = "KeyboardPathFinder";
        $mock = $this->getMockBuilder($classname)
                     ->disableOriginalConstructor()
                     ->getMock();

        $reflectedClass = new ReflectionClass($classname);
        $constructor = $reflectedClass->getConstructor();

        // Find optimum path will be called if input is passed in
        $mock->expects($this->once())
             ->method("findOptimumPath")
             ->with("Hello");
        $constructor->invoke($mock, "Hello");

        // Find optimum path won't be called when nothing is passed in
        $mock->expects($this->never())
             ->method("findOptimumPath");
        $constructor->invoke($mock);

    }

    public function testFindOptimumPathValidation() {
        $oFinder = new KeyboardPathFinder();
        try {
            $oFinder->findOptimumPath(null);
            $this->fail("No exception raise while finding null.");
        } catch (Exception $e) {
            $this->assertEquals('Empty input!', $e->getMessage());
        }

        try {
            $oFinder->findOptimumPath("");
            $this->fail("No exception raise while finding empty string.");
        } catch (Exception $e) {
            $this->assertEquals('Empty input!', $e->getMessage());
        }

        try {
            $oFinder->findOptimumPath("\011");
            //$this->fail("No exception raise while finding empty string.");
        } catch (Exception $e) {
            $this->assertEquals('Empty input!', $e->getMessage());
        }

        try {
            $oFinder->findOptimumPath("A");
        } catch (Exception $e) {
            $this->fail("Exception raise while finding 'A'");
        }
    }

    public function testFindOptionPathWithFindingSingleCharacter() {
        $oFinder = new KeyboardPathFinder();
        try {
            $oFinder->findOptimumPath("A");
            $this->assertEquals(1, count($oFinder->getFoundPath()));

            $oFinder->findOptimumPath(0);
            $this->assertEquals(1, count($oFinder->getFoundPath()));
            $oFinder->findOptimumPath(ScreenKeyboard::SPACEBAR);
            $this->assertEquals(1, count($oFinder->getFoundPath()));
            $oFinder->findOptimumPath(ScreenKeyboard::BACKSPACE);
            $this->assertEquals(1, count($oFinder->getFoundPath()));
            $oFinder->findOptimumPath("\010");
            $this->assertEquals(1, count($oFinder->getFoundPath()));
            $oFinder->findOptimumPath(" ");
            $this->assertEquals(1, count($oFinder->getFoundPath()));
        } catch(Exception $e) {
            $this->fail("Exception raise while testing on finding single character path. (" . $e->getMessage() . ")" );
        }
    }

    public function testFindOptimumPathWithSpace() {
        $oFinder = new KeyboardPathFinder(" #");
        $aPath = $oFinder->getFoundPath();
        $this->assertEquals(array(
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::UP,
            KeyboardPathFinder::ENTER), $aPath);

        $oFinder = new KeyboardPathFinder(" I");
        $aPath = $oFinder->getFoundPath();
        $this->assertEquals(array(
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::DOWN,
            KeyboardPathFinder::ENTER), $aPath);

        $oFinder = new KeyboardPathFinder(" L");
        $aPath = $oFinder->getFoundPath();
        $this->assertEquals(array(
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::UP,
            KeyboardPathFinder::UP,
            KeyboardPathFinder::UP,
            KeyboardPathFinder::LEFT,
            KeyboardPathFinder::ENTER), $aPath);

        $oFinder = new KeyboardPathFinder("PI");
        $aPath = $oFinder->getFoundPath();
        $this->assertEquals(array(
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::UP,
            KeyboardPathFinder::DOWN,
            KeyboardPathFinder::ENTER), $aPath);

        $oFinder = new KeyboardPathFinder("IQ");
        $aPath = $oFinder->getFoundPath();
        $this->assertEquals(array(
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::RIGHT,
            KeyboardPathFinder::RIGHT,
            KeyboardPathFinder::RIGHT,
            KeyboardPathFinder::RIGHT,
            KeyboardPathFinder::RIGHT,
            KeyboardPathFinder::RIGHT,
            KeyboardPathFinder::RIGHT,
            KeyboardPathFinder::RIGHT,
            KeyboardPathFinder::ENTER), $aPath);
    }

    public function testFindOptionPathAllInSameRow() {
        $oFinder = new KeyboardPathFinder("AB");
        $aPath = $oFinder->getFoundPath();
        $this->assertEquals(array(
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::RIGHT,
            KeyboardPathFinder::ENTER), $aPath);

        $oFinder->findOptimumPath("ABC");
        $aPath = $oFinder->getFoundPath();
        $this->assertEquals(array(
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::RIGHT,
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::RIGHT,
            KeyboardPathFinder::ENTER), $aPath);

        $oFinder->findOptimumPath("ZA");
        $aPath = $oFinder->getFoundPath();
        $this->assertEquals(array(
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::RIGHT,
            KeyboardPathFinder::ENTER), $aPath);

        $oFinder->findOptimumPath("abc");
        $aPath = $oFinder->getFoundPath();
        $this->assertEquals(array(
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::RIGHT,
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::RIGHT,
            KeyboardPathFinder::ENTER), $aPath);

        $oFinder->findOptimumPath("az");
        $aPath = $oFinder->getFoundPath();
        $this->assertEquals(array(
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::LEFT,
            KeyboardPathFinder::ENTER), $aPath);

        $oFinder->findOptimumPath("123");
        $aPath = $oFinder->getFoundPath();
        $this->assertEquals(array(
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::RIGHT,
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::RIGHT,
            KeyboardPathFinder::ENTER), $aPath);

        $oFinder->findOptimumPath("321");
        $aPath = $oFinder->getFoundPath();
        $this->assertEquals(array(
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::LEFT,
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::LEFT,
            KeyboardPathFinder::ENTER), $aPath);

        $oFinder->findOptimumPath(">.");
        $aPath = $oFinder->getFoundPath();
        $this->assertEquals(array(
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::RIGHT,
            KeyboardPathFinder::RIGHT,
            KeyboardPathFinder::ENTER), $aPath);

        $oFinder->findOptimumPath("=`");
        $aPath = $oFinder->getFoundPath();
        $this->assertEquals(array(
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::RIGHT,
            KeyboardPathFinder::RIGHT,
            KeyboardPathFinder::ENTER), $aPath);

        $oFinder->findOptimumPath("`=");
        $aPath = $oFinder->getFoundPath();
        $this->assertEquals(array(
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::LEFT,
            KeyboardPathFinder::LEFT,
            KeyboardPathFinder::ENTER), $aPath);
    }

    public function testFindOptionPath() {
        $oFinder = new KeyboardPathFinder("Aa");
        $aPath = $oFinder->getFoundPath();
        $this->assertEquals(array(
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::DOWN,
            KeyboardPathFinder::ENTER), $aPath);

        $oFinder = new KeyboardPathFinder("A\010");
        $aPath = $oFinder->getFoundPath();
        $this->assertEquals(array(
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::UP,
            KeyboardPathFinder::LEFT,
            KeyboardPathFinder::ENTER), $aPath);

        $oFinder = new KeyboardPathFinder("\010-");
        $aPath = $oFinder->getFoundPath();
        $this->assertEquals(array(
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::UP,
            KeyboardPathFinder::ENTER), $aPath);

        $oFinder = new KeyboardPathFinder("\010Z");
        $aPath = $oFinder->getFoundPath();
        $this->assertEquals(array(
            KeyboardPathFinder::ENTER,
            KeyboardPathFinder::DOWN,
            KeyboardPathFinder::ENTER), $aPath);
    }
}
?>