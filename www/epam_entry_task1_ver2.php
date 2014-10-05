<?php
$scriptAddress = $_SERVER[SCRIPT_FILENAME];
$fileContents = htmlspecialchars(file_get_contents("$scriptAddress"));
echo '<pre>'.$fileContents . '</pre>';