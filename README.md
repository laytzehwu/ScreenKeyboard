<h1>Screen keyboard simulator and optimum path finder.</h1>
<div>
<p>PHPUnit is used for the unit test and run below command to see unit test result:</p>
 <center>sh test.sh</center>
</div>
<ul>
 <li>Class Screenkeyboard is the screen keyboard simulator which simulate key moving.</li>
 <li>Class KeyboardPathFinder find the optimum path of input string. Below is the sample code of finding optimum path:</li>
</ul>
<pre>
$sInput = ‘find the optimum path for this sentence’;
$oFinder = new KeyboardPathFinder($sInput);
$aPath = $oFinder->getFoundPath();
reset($aPath);
while(list($iIdx, $iAction) = each($aPath)) {
    switch ($iAction) {
        case KeyboardPathFinder::LEFT: // Move left
            // ...
            break;
        case KeyboardPathFinder::RIGHT: // Move right
            // ...
            break;
        case KeyboardPathFinder::UP: // Move up
            // ...
            break;
        case KeyboardPathFinder::DOWN: // Move down
            // ...
            break;
        case KeyboardPathFinder::ENTER: // Press enter
            // ...
            break;
    }
}
</pre>
