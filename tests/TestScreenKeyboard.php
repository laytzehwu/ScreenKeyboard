<?php
class TestScreenKeyboard extends PHPUnit_Framework_TestCase
{
    public function testGetSetCurKey() {
        $keyboard = new ScreenKeyboard();

        // Basic key set
        $this->assertEquals('A', $keyboard->getCurKey());
        $keyboard->setCurKey('B');
        $this->assertEquals('B', $keyboard->getCurKey());
        $keyboard->setCurKey('a');
        $this->assertEquals('a', $keyboard->getCurKey());
        $keyboard->setCurKey('a');
        $this->assertEquals('a', $keyboard->getCurKey());

        // Only set the first charactor of input
        $keyboard->setCurKey('abc');
        $this->assertEquals('a', $keyboard->getCurKey());
        $keyboard->setCurKey(123);
        $this->assertEquals('1', $keyboard->getCurKey());

        // Special keys testing
        $keyboard->setCurKey(ScreenKeyboard::$SPACEBAR);
        $this->assertEquals(ScreenKeyboard::$SPACEBAR, $keyboard->getCurKey());
        $keyboard->setCurKey(ScreenKeyboard::$BACKSPACE);
        $this->assertEquals(ScreenKeyboard::$BACKSPACE, $keyboard->getCurKey());
    }

    public function testGetPositionKey() {
        // Test whole keyboard
        // A .. Z
        for($i=0;$i<26;$i++) {
            $this->assertEquals(chr(65+$i), ScreenKeyboard::getPositionKey(0,$i));
        }

        // a.. z
        for($i=0;$i<26;$i++) {
            $this->assertEquals(chr(97+$i), ScreenKeyboard::getPositionKey(1,$i));
        }

        $sTestString = "0123456789!@#$%^&*()?/|\\+-";
        for($i=0;$i<26;$i++) {
            $this->assertEquals($sTestString[$i], ScreenKeyboard::getPositionKey(2,$i));
        }

        $sTestString = "`~[]{}<>        .,;:'\"_=\010\010";
        for($i=0;$i<26;$i++) {
            $this->assertEquals($sTestString[$i], ScreenKeyboard::getPositionKey(3,$i));
        }

        // Sample test on negative position
        $this->assertEquals(ScreenKeyBoard::$BACKSPACE, ScreenKeyboard::getPositionKey(-1,-1));
        $this->assertEquals("Y", ScreenKeyBoard::getPositionKey(0,-2));
        $this->assertEquals("~", ScreenKeyBoard::getPositionKey(-1,1));

    }

    public function testGetKeyPosition() {
        // A .. Z
        for($i=0;$i<26;$i++) {
            list($iRow, $iCol) = ScreenKeyboard::getKeyPosition(chr(65+$i));
            $this->assertEquals(0, $iRow);
            $this->assertEquals($i, $iCol);
        }
        // a.. z
        for($i=0;$i<26;$i++) {
            list($iRow, $iCol) = ScreenKeyboard::getKeyPosition(chr(97+$i));
            $this->assertEquals(1, $iRow);
            $this->assertEquals($i, $iCol);
        }

        $sTestString = "0123456789!@#$%^&*()?/|\\+-";
        for($i=0;$i<26;$i++) {
            list($iRow, $iCol) = ScreenKeyboard::getKeyPosition($sTestString[$i]);
            $this->assertEquals(2, $iRow);
            $this->assertEquals($i, $iCol);
        }

        $sTestString = "`~[]{}<>        .,;:'\"_=\010\010";
        for($i=0;$i<26;$i++) {
            $sKeyToTest = $sTestString[$i];
            if($sKeyToTest == ScreenKeyboard::$SPACEBAR) continue;
            if($sKeyToTest == ScreenKeyboard::$BACKSPACE) continue;
            list($iRow, $iCol) = ScreenKeyboard::getKeyPosition($sKeyToTest);
            $this->assertEquals(3, $iRow);
            $this->assertEquals($i, $iCol);
        }
        // Special key
        list($iRow, $iCol) = ScreenKeyboard::getKeyPosition(ScreenKeyboard::$SPACEBAR);
        $this->assertEquals(3, $iRow);
        $this->assertEquals(8, $iCol);
        list($iRow, $iCol) = ScreenKeyboard::getKeyPosition(ScreenKeyboard::$BACKSPACE);
        $this->assertEquals(3, $iRow);
        $this->assertEquals(24, $iCol);

        // Test fail
        $this->setExpectedException('Exception', 'Empty key!');
        ScreenKeyboard::getKeyPosition("");
        ScreenKeyboard::getKeyPosition(null);

        $this->setExpectedException('Exception', 'Key not found');
        ScreenKeyboard::getKeyPosition("\011");

    }

    public function testMoveUp() {
        $keyboard = new ScreenKeyboard();
        // Normal case
        $keyboard->setCurKey('a');
        $keyboard->moveUp();
        $this->assertEquals('A', $keyboard->getCurKey());
        $keyboard->setCurKey('b');
        $keyboard->moveUp();
        $this->assertEquals('B', $keyboard->getCurKey());

        // Spacebar
        $keyboard->setCurKey(ScreenKeyboard::$SPACEBAR);
        $keyboard->moveUp();
        $this->assertEquals('#', $keyboard->getCurKey());

        // To space bar
        $keyboard->setCurKey("I");
        $keyboard->moveUp();
        $this->assertEquals(ScreenKeyboard::$SPACEBAR, $keyboard->getCurKey());
        $keyboard->setCurKey("J");
        $keyboard->moveUp();
        $this->assertEquals(ScreenKeyboard::$SPACEBAR, $keyboard->getCurKey());
        $keyboard->setCurKey("K");
        $keyboard->moveUp();
        $this->assertEquals(ScreenKeyboard::$SPACEBAR, $keyboard->getCurKey());
        $keyboard->setCurKey("L");
        $keyboard->moveUp();
        $this->assertEquals(ScreenKeyboard::$SPACEBAR, $keyboard->getCurKey());
        $keyboard->setCurKey("M");
        $keyboard->moveUp();
        $this->assertEquals(ScreenKeyboard::$SPACEBAR, $keyboard->getCurKey());
        $keyboard->setCurKey("N");
        $keyboard->moveUp();
        $this->assertEquals(ScreenKeyboard::$SPACEBAR, $keyboard->getCurKey());
        $keyboard->setCurKey("O");
        $keyboard->moveUp();
        $this->assertEquals(ScreenKeyboard::$SPACEBAR, $keyboard->getCurKey());
        $keyboard->setCurKey("P");
        $keyboard->moveUp();
        $this->assertEquals(ScreenKeyboard::$SPACEBAR, $keyboard->getCurKey());

        // Backspace
        $keyboard->setCurKey(ScreenKeyboard::$BACKSPACE);
        $keyboard->moveUp();
        $this->assertEquals('-', $keyboard->getCurKey());

        // To Backspace
        $keyboard->setCurKey("Y");
        $keyboard->moveUp();
        $this->assertEquals(ScreenKeyboard::$BACKSPACE, $keyboard->getCurKey());
        $keyboard->setCurKey("Z");
        $keyboard->moveUp();
        $this->assertEquals(ScreenKeyboard::$BACKSPACE, $keyboard->getCurKey());

        // Test move over keyboard
        $keyboard->setCurKey('A');
        $keyboard->moveUp();
        $this->assertEquals('`', $keyboard->getCurKey());

    }

    public function testMoveDown() {
        $keyboard = new ScreenKeyboard();
        // Normal case
        $keyboard->setCurKey('A');
        $keyboard->moveDown();
        $this->assertEquals('a', $keyboard->getCurKey());
        $keyboard->setCurKey('B');
        $keyboard->moveDown();
        $this->assertEquals('b', $keyboard->getCurKey());

        // Spacebar
        $keyboard->setCurKey(ScreenKeyboard::$SPACEBAR);
        $keyboard->moveDown();
        $this->assertEquals('I', $keyboard->getCurKey());

        // To spacebar
        $keyboard->setCurKey("8");
        $keyboard->moveDown();
        $this->assertEquals(ScreenKeyboard::$SPACEBAR, $keyboard->getCurKey());
        $keyboard->setCurKey("9");
        $keyboard->moveDown();
        $this->assertEquals(ScreenKeyboard::$SPACEBAR, $keyboard->getCurKey());
        $keyboard->setCurKey("!");
        $keyboard->moveDown();
        $this->assertEquals(ScreenKeyboard::$SPACEBAR, $keyboard->getCurKey());
        $keyboard->setCurKey("@");
        $keyboard->moveDown();
        $this->assertEquals(ScreenKeyboard::$SPACEBAR, $keyboard->getCurKey());
        $keyboard->setCurKey("#");
        $keyboard->moveDown();
        $this->assertEquals(ScreenKeyboard::$SPACEBAR, $keyboard->getCurKey());
        $keyboard->setCurKey("$");
        $keyboard->moveDown();
        $this->assertEquals(ScreenKeyboard::$SPACEBAR, $keyboard->getCurKey());
        $keyboard->setCurKey("%");
        $keyboard->moveDown();
        $this->assertEquals(ScreenKeyboard::$SPACEBAR, $keyboard->getCurKey());
        $keyboard->setCurKey("^");
        $keyboard->moveDown();
        $this->assertEquals(ScreenKeyboard::$SPACEBAR, $keyboard->getCurKey());

        // Backspace
        $keyboard->setCurKey(ScreenKeyboard::$BACKSPACE);
        $keyboard->moveDown();
        $this->assertEquals('Z', $keyboard->getCurKey());

        // To backspace
        $keyboard->setCurKey("+");
        $keyboard->moveDown();
        $this->assertEquals(ScreenKeyboard::$BACKSPACE, $keyboard->getCurKey());
        $keyboard->setCurKey("-");
        $keyboard->moveDown();
        $this->assertEquals(ScreenKeyboard::$BACKSPACE, $keyboard->getCurKey());

        // Test move over keyboard
        $keyboard->setCurKey('`');
        $keyboard->moveDown();
        $this->assertEquals('A', $keyboard->getCurKey());
    }

    public function testKeysInSameRow() {
        $this->assertTrue(ScreenKeyboard::keysInSameRow("A", "A"));
        $this->assertTrue(ScreenKeyboard::keysInSameRow("B", "A"));
        $this->assertTrue(ScreenKeyboard::keysInSameRow("A", "B"));
        $this->assertFalse(ScreenKeyboard::keysInSameRow("A", "0"));
        $this->assertTrue(ScreenKeyboard::keysInSameRow(ScreenKeyboard::$SPACEBAR, ScreenKeyboard::$BACKSPACE));
        $this->assertTrue(ScreenKeyboard::keysInSameRow(ScreenKeyboard::$BACKSPACE, ScreenKeyboard::$SPACEBAR));
    }
}
?>