<?php

//for tests
echo checkInput('echo "bang")//;') . '<br>';// FALSE
echo checkInput('www.exe') . '<br>';// www.exe
echo checkInput('w......ww..ex.e') . '<br>';// wwwex.e
echo checkInput('!@#$%^&*()_+.)(**&^%$#@@AEWQghj') . '<br>';// _.ghj
echo checkInput('Русские буквы.eaa') . '<br>';// FALSE
echo checkInput('BIG LETTERS') . '<br>';// FALSE
echo checkInput('12') . '<br>';// FALSE
echo checkInput('Æab.as') . '<br>';// ab.as
echo checkInput(' ') . '<br>';// FALSE
echo checkInput('w ww/r//n
	www

	wwww
wwwww
	wwwww.doc') . '<br>';// wwwrnwwwwwwwwwwwwwwwww.doc
echo checkInput('wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww50symbolswwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww100symbolswwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww150symbolswwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww200symbolswwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww250symbols.wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww50symbolswwwwwwwwwwwwwwwwwwwwwwwwwwwwttttwwwwwwwwwwwwwwwwwwwwwww
	wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww
	wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww200symbolsffffffffffffffffffffffff') . '<br>';//... 150symbol.www....

//the function itself follows below: returns FALSE if the filename or extension contain no permitted characters or no dots(.), i.e. cannot be rendered as a legal file name without adding something to it 
function checkInput($str)
{
	$str = preg_replace('/[^a-z_0-9.]/', "", $str);// remove all but permitted entities
	$str = preg_replace('/\.(?=.*\.)/', "", $str);// if there is a dot preceding any numner of any entities and a dot, remove the first of the two dots (removes all but the last of the dots) 
	$str = preg_replace('/((?<=\.).{50}).*/', "$1", $str);// leave only 50 characters after the dot, remove the rest of the characters following after the dot
	$nameExt=explode('.', $str);// divide the string into two parts: file name and file extension
	$name = $nameExt[0];
	$extension = $nameExt[1];
	if ($name == '' || $extension == '' || strpos($str, '.') == NULL) return FALSE;// return FALSE in eithe case: file name is empty, file extension is empty, no dots contained in the string (cannot separate file name from its extension) 
	if (strlen($str) > 200)//if the string is too long, remove as little as possible from its name (the extension is already no longer than 50 characters) 
	{
		$name = substr($name, 0, 200-strlen($extension)-1);
	}
	$str = $name.".".$extension;
	return $str;
}
