<?php
$scriptAddress = $_SERVER['SCRIPT_FILENAME'];
$fileContents = file_get_contents("$scriptAddress");
echo $fileContents;