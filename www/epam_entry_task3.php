<?php

//для тестов
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
//искомая функция - возвращает FALSE если имя файла или его расширение вообще не содержат допустимых символов.
function checkInput($str)
{
	$str = preg_replace('/[^a-z_0-9.]/', "", $str);// удаляем все недопустимые символы
	$str = preg_replace('/\.(?=.*\.)/', '', $str);// если до любого количества любых символов И точки есть точка, убрать ее (оставляем последнюю точку). 
	$str = preg_replace('/((?<=\.).{50}).*/', '$1', $str);// оставляем 50 символов после точки, остальные после этих 50 удаляем
	$nameExt=explode('.', $str);//поделим строку на имя файла и его расширение
	$name = $nameExt[0];
	$extension = $nameExt[1];
	if ($name == '' || $extension == '' || strpos($str, '.') == NULL) return FALSE;//если имя файла или его расширение не содержат символов, вернем FALSE
	if (strlen($str) > 200)// если  строка длинная, то минимально порежем конец имени файла, а не расширение
	{
		$name = substr($name, 0, 200-strlen($extension)-1);
	}
	$str = $name.".".$extension;
	return $str;
}
