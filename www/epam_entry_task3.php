<?php

//для тестов
echo checkInput('echo "bang")//;');
echo checkInput('www.exe');
echo checkInput('w......ww..ex.e');
echo checkInput('!@#$%^&*()_+.)(**&^%$#@@AEWQghj');
echo checkInput('Русские буквы');
echo checkInput('BIG LETTERS');
echo checkInput('12');
echo checkInput('');
echo checkInput('w
	ww/r//n

	www


	wwww
		wwwww
	wwwww');
echo checkInput('wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww50symbolswwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww100symbolswwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww150symbolswwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww200symbolswwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww250symbols.wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww50symbolswwwwwwwwwwwwwwwwwwwwwwwwwwwwttttwwwwwwwwwwwwwwwwwwwwwww
	wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww
	wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww200symbolsffffffffffffffffffffffff');
echo checkInput('wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww50symbolswwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww100symbolswwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww150symbolswwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww200symbolswwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww250symbols');
//искомая функция
function checkInput($str)
{
	$str = preg_replace('/[^a-z_0-9.]/', "", $str);// удаляем все недопустимые символы
	$str = preg_replace('/((?<=\.)[^.]*)\./', '$1', $str);// если перед и после любого количества любых символов И точки есть точка, убрать ее. 
	$str = preg_replace('/((?<=\.).{50}).*/', '$1', $str);// не удалять 50 символов после точки, остальные после этих 50 - Weg!
	if ((strlen($str) > 200)&&(strpos($str,'.')))// если есть точка, и строка длинная, то минимально порежем конец имени файла, а не расширение
	{
		$nameExt=explode('.', $str);
		$name = $nameExt[0];
		$extension = $nameExt[1];
		$name = substr($name, 0, 200-strlen($extension)-1);
		$str = $name.".".$extension;	
	}
	if (strlen($str) > 200)
	{
		$str = preg_replace('/(.{200}).*/', '$1', $str);//раз в названии нет точек, то отрезать все, что за пределами дозволенных 200 символов от начала
	}
	return $str.'<br>';
}
