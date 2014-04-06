<?php
require_once("ScreenKeyboard.php");
class KeyboardPathFinder {

    const LEFT=1;
    const RIGHT=2;
    const UP=3;
    const DOWN=4;

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

        $bTheyAreInSameRow = ScreenKeyboard::keysInSameRow($sKey1, $sKey2);
        if($bTheyAreInSameRow) {
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

            $sNextKey1 = $sLeftKey;
            $aNewPath1 = $aPath;
            $aNewPath1[] = self::LEFT;
            $sNextKey2 = $sRightKey;
            $aNewPath2 = $aPath;
            $aNewPath2[] = self::RIGHT;

        } else {
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
            $sNextKey1 = $sTopKey;
            $aNewPath1 = $aPath;
            $aNewPath1[] = self::UP;
            $sNextKey2 = $sBottomKey;
            $aNewPath2 = $aPath;
            $aNewPath2[] = self::DOWN;
        }

        $bFoundInPath1 = false;
        $bFoundInPath2 = false;

        if(self::seekKeysPath($sNextKey1, $sKey2, $aNewPath1, $sTriedkeys)) {
            $bFoundInPath1 = true;
        }
        if(self::seekKeysPath($sNextKey2, $sKey2, $aNewPath2, $sTriedkeys)) {
            $bFoundInPath2 = true;
        }
        if($bFoundInPath1 && $bFoundInPath2) {
            if(count($aNewPath1) < count($aNewPath2)) {
                // Take the shorter
                $aPath = $aNewPath1;
            } else {
                $aPath = $aNewPath2;
            }
        } else if($bFoundInPath1) {
            $aPath = $aNewPath1;
        } else if($bFoundInPath2) {
            $aPath = $aNewPath2;
        }
        return $bFoundInPath1 || $bFoundInPath2;
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
}
?>