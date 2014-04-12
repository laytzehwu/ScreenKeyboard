<?php
require_once("ScreenKeyboard.php");
class KeyboardPathFinder {

    // *************************************************************************** Static
    const LEFT=1;
    const RIGHT=2;
    const UP=3;
    const DOWN=4;
    const ENTER = 5;
    /**
     * Find path between keys
     * @param - String - Starting key
     * @param - String - Target key
     * @param - Array - Seeking path
     * @param - String - Tried keys
     * @return - Boolean - Found?
    */
    protected function seekKeysPath($sKey1, $sKey2, &$aPath, $sTriedkeys = "") {
        if(strpos($sTriedkeys, $sKey1) === false) {
            $sTriedkeys .= $sKey1;
        } else {
            // Skip if it has been done
            return false;
        }

        // Try keys around
        $sLeftKey = ScreenKeyboard::keyLeft($sKey1);
        if($sLeftKey === $sKey2) {
            $aPath[] = self::LEFT;
            return true;
        }

        $sRightKey = ScreenKeyboard::keyRight($sKey1);
        if($sRightKey === $sKey2) {
            $aPath[] = self::RIGHT;
            return true;
        }

        $sTopKey = ScreenKeyboard::keyOnTop($sKey1);
        if($sTopKey === $sKey2) {
            $aPath[] = self::UP;
            return true;
        }

        $sBottomKey = ScreenKeyboard::keyAbove($sKey1);
        if($sBottomKey === $sKey2) {
            $aPath[] = self::DOWN;
            return true;
        }

        $bTheyAreInSameRow = ScreenKeyboard::keysInSameRow($sKey1, $sKey2);
        // Plan to have next tries
        $arrNextKeys = array();
        if($bTheyAreInSameRow) {
            $arrNextKeys[] = array(
                "key" => $sLeftKey,
                "path" => array_merge($aPath, array(self::LEFT))
            );
            $arrNextKeys[] = array(
                "key" => $sRightKey,
                "path" => array_merge($aPath, array(self::RIGHT))
            );

            // Add space bar retry
            if($sTopKey === ScreenKeyboard::SPACEBAR) {
                $arrNextKeys[] = array(
                    "key" => $sTopKey,
                    "path" => array_merge($aPath, array(self::UP))
                );
            } else if($sBottomKey === ScreenKeyboard::SPACEBAR) {
                $arrNextKeys[] = array(
                    "key" => $sBottomKey,
                    "path" => array_merge($aPath, array(self::DOWN))
                );
            }

        } else {

            $arrNextKeys[] = array(
                "key" => $sTopKey,
                "path" => array_merge($aPath, array(self::UP))
            );
            $arrNextKeys[] = array(
                "key" => $sBottomKey,
                "path" => array_merge($aPath, array(self::DOWN))
            );

        }

        // Next try
        reset($arrNextKeys);
        while(list($iIdx, $arrNextTry) = each($arrNextKeys)) {
            if (!self::seekKeysPath($arrNextTry["key"], $sKey2, $arrNextTry["path"], $sTriedkeys)) {
                // Clear the fail try
                unset($arrNextKeys[$iIdx]);
                continue;
            }
            $arrNextKeys[$iIdx] = $arrNextTry;
        }
        if (empty($arrNextKeys)) {
            return false;
        }

        // Take the update from next try
        $iPreviousStep = count($aPath);
        reset($arrNextKeys);
        while(list($iIdx, $arrNextTry) = each($arrNextKeys)) {
            if($iPreviousStep === count($aPath)) {
                $aPath = $arrNextTry["path"];
                continue;
            }

            if(count($aPath) <= count($arrNextTry["path"])) continue;

            $aPath = $arrNextTry["path"];
        }
        return true;

    }

    /**
     * Search path from key1 to key2
     * @param - String - Key1
     * @param - String - Key2
     * @return - Array - Movement list.
    */
    public function getKeyToKeyPath($sKey1, $sKey2) {
        $sKey1 = ScreenKeyboard::validKey($sKey1);
        $sKey2 = ScreenKeyboard::validKey($sKey2);
        $aPath = array();
        if($sKey1 === $sKey2) {
            return $aPath;
        }
        if(self::seekKeysPath($sKey1, $sKey2, $aPath)) {
            return $aPath;
        }
        return null;
    }
    // ******************************************************************** End of Static
    private $arrPath;
    /**
     * Constructor of KeyboardPathFinder. It start finding path when the input is passing in.
     * @param - String - Input
    */
    function __construct($sInput="") {
        $this->arrPath = array();
        if(!empty($sInput)) $this->findOptimumPath($sInput);
    }
    /**
     * Find input optimum path
     * @param - String - Input
    */
    public function findOptimumPath($sInput) {
        $sInput = strval($sInput);
        $iLen = strlen($sInput);
        if($iLen == 0) throw new Exception('Empty input!');
        $this->arrPath = array();

        $sCurKey = ScreenKeyboard::validKey($sInput);
        $aPath = self::getKeyToKeyPath($sCurKey, $sCurKey);
        $this->arrPath[] = self::ENTER;
        while($iLen > 1) {
            $sInput = substr($sInput, 1);
            $sNextKey = ScreenKeyboard::validKey($sInput);
            $aPath = self::getKeyToKeyPath($sCurKey, $sNextKey);
            if(count($aPath) > 0) {
                $this->arrPath = array_merge($this->arrPath, $aPath);
            }
            $this->arrPath[] = self::ENTER;
            $sCurKey = $sNextKey;
            $iLen = strlen($sInput);
        }
    }
    /**
     * Return found path
     * @return - Array - Path
    */
    public function getFoundPath() {
        return $this->arrPath;
    }
}
?>