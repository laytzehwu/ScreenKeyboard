<?php

class ScreenKeyboard {

    const SPACEBAR = " ";
    const BACKSPACE = "\010";

    private $cCurKey = 'A'; // Current key
    private static $aKeyMatrix = array(
        0 => "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
        1 => "abcdefghijklmnopqrstuvwxyz",
        2 => "0123456789!@#$%^&*()?/|\\+-",
        3 => "`~[]{}<>        .,;:'\"_=\010\010"
    );

    /**
     * Get current key of keyboard
     * @return - String - Current key
    */
    public function getCurKey() {
        return $this->cCurKey;
    }

    /**
     * Set current key of keyboard
     * @param - String - Current key
    */
    public function setCurKey($sKey) {

        switch($sKey) {
            case self::SPACEBAR:
                $this->cCurKey = self::SPACEBAR;
                break;
            case self::BACKSPACE:
                $this->cCurKey = self::BACKSPACE;
                break;
            default:
                $this->cCurKey = substr(strval($sKey), 0,1);
                break;
        }

    }

    /**
     * Get key of position
     * @param - integer - row number 0 ~ 3
     * @param - integer -column number 0 ~ 25
     * @return - String - Key in the position
    */
    public function getPositionKey($iRow, $iCol) {
        $sKey = null;
        $iRow = intval($iRow);
        $iCol = intval($iCol);
        // Handle negative
        while($iRow < 0) $iRow += 4;
        while($iCol < 0) $iCol += 26;

        // Make sure it is in bound
        $iRow = $iRow % 4;
        $iCol = $iCol % 26;
        switch($iRow) {
            case 0:
            case 1:
            case 2:
                $sKey = self::$aKeyMatrix[$iRow][$iCol];
                break;
            case 3:

                if($iCol > 7 && $iCol < 16) {
                    $sKey = self::SPACEBAR;
                } else if($iCol > 23) {
                    $sKey = self::BACKSPACE;
                } else {
                    $sKey = self::$aKeyMatrix[$iRow][$iCol];
                }
                if($iCol < 8) {
                    $sKey = self::$aKeyMatrix[$iRow][$iCol];
                } else if ($iCol < 16) {
                    $sKey = self::SPACEBAR;
                } else if ($iCol < 24) {
                    $sKey = self::$aKeyMatrix[$iRow][$iCol];
                }
                break;
        }
        return $sKey;
    }

    /**
     * Validate key - to make sure pass is valid key
     * @param - String - valuable to be validated
     * @return - String - validated keys
    */
    public function validKey($sKey) {
        $sKey = strval($sKey);
        if($sKey === '') {
            throw new Exception('Empty key!');
        }
        return $sKey[0];
    }

    /**
     * Check and return key position
     * @param - String - Key
     * @return - (int, int) - Row and column no
    */
    public function getKeyPosition($sKey) {
        $sKey = self::validKey($sKey);
        reset(self::$aKeyMatrix);
        while(list($iRow, $sRow) = each(self::$aKeyMatrix)) {
            $iPos = strpos($sRow, $sKey);
            if($iPos === false) continue;
            return array($iRow, $iPos);
        }

        throw new Exception('Key not found');
    }

    /**
     * Check key on top of given key
     * @param - String - Key
     * @return - String - Key on top
    */
    public function keyOnTop($sKey) {
        $sKey = self::validKey($sKey);
        switch($sKey) {
            case self::SPACEBAR:
                $sNextKey = "#";
                break;
            case self::BACKSPACE:
                $sNextKey = "-";
                break;
            default:
                list($iRow, $iCol) = self::getKeyPosition($sKey);
                $iRow --;
                $sNextKey = self::getPositionKey($iRow, $iCol);
                break;
        }
        return $sNextKey;
    }

    /**
     * Move up
    */
    public function moveUp() {

        $sCurKey = $this->getCurKey();
        $sNextKey = self::keyOnTop($sCurKey);
        $this->setCurKey($sNextKey);
    }

    /**
     * Check key above of given key
     * @param - String - Key
     * @return - String - Key above
    */
    public function keyAbove($sKey) {
        $sKey = self::validKey($sKey);
        switch($sKey) {
            case self::SPACEBAR:
                $sNextKey = "I";
                break;
            case self::BACKSPACE:
                $sNextKey = "Z";
                break;
            default:
                list($iRow, $iCol) = self::getKeyPosition($sKey);
                $iRow ++;
                $sNextKey = self::getPositionKey($iRow, $iCol);
                break;
        }
        return $sNextKey;
    }

    /**
     * Move down
    */
    public function moveDown() {
        $sCurKey = $this->getCurKey();
        $sNextKey = self::keyAbove($sCurKey);
        $this->setCurKey($sNextKey);
    }

    /**
     * Get key on left
     * @param - String - Key to check
     * @return - String - Key on left
    */
    public function keyLeft($sKey) {
        list($iRow, $iCol) = self::getKeyPosition($sKey);
        $iCol --;
        return self::getPositionKey($iRow, $iCol);
    }

    /**
     *Get key on right
     * @param - String - Key to check
     * @return - String - Key on left
    */
    public function keyRight($sKey) {
        switch($sKey) {
            case self::SPACEBAR:
                $sNextKey = ".";
                break;
            case self::BACKSPACE:
                $sNextKey = "`";
                break;
            default:
                list($iRow, $iCol) = self::getKeyPosition($sKey);
                $iCol ++;
                $sNextKey = self::getPositionKey($iRow, $iCol);
                break;
        }
        return $sNextKey;
    }

    /**
     * Check keys in same row
     * @param - String - key1
     * @param - String - key2
     * @return - Boolean - The 2 keys in same fow
    */
    public function keysInSameRow($sKey1, $sKey2) {
        $aKeyMatrix = self::$aKeyMatrix;
        $iLen = count($aKeyMatrix);
        for($i=0;$i<$iLen;$i++) {
            if(strpos($aKeyMatrix[$i], $sKey1) === false) continue;
            if(strpos($aKeyMatrix[$i], $sKey2) === false) continue;
            return true;
        }
        return false;
    }
}
?>