<?php

//для тестов
checkInput('12.4ю.qw.нгнкоadfrwgwgf......................sgffgdfgrgghtteQW...E!@..@$.%%@^яя.яяя.)((*&^%');
checkInput('124qweQWE...');
checkInput('12');
checkInput('');
checkInput('fg.wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww
	wwwwwwwwwwwwwwwwwwwwwwwwwwwwttttwwwwwwwwwwwwwwwwwwwwwww
	wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww
	wwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww200ffffffffffggggggggggg
	gggggggggggggggggggggggggggggggggggggggg
	hhhhhhhhhhhhhhhhjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj
	jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj
	kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk');
checkInput('fgwwwwwjjjj
	jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj
	kkkkkkkkkkkkkkkkkkkkkkkkkk.kkkkkkkkkkkkkkkj5gdhdntr5hbe5574jjl;i[///.....ytttyyyyyyyyyyyyyyyyyykkkkkkkkkkkkk');
checkInput('fgwwwwwjjjj
	jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj
	kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkj5gdhdntr5hbe5574jjl;i[///ytttyyyyyyyyyyyyyyyyyykkkkkkkkkkkkk
	gggggggggggggggggggggggggggggggggggggggggggggggggg
	ggggggggggggggggggggggggggggggggggggggggggggggggggggg
	ggggggggggggggggggggggggggggggggggttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttend');

//искомая функция
function checkInput($str)
{
	$str = preg_replace('/[^a-z_0-9.]/', "", $str);// удаляем все недопустимые символы
	$str = preg_replace('/((?<=\.)[^.]*)\./', '$1', $str);// если перед и после любого количества любых символов И точки есть точка, убрать ее. 
	$str = preg_replace('/((?<=\.).{50}).*/', '$1', $str);// не удалять 50 символов после точки, остальные после этих 50 - Weg!
	if ((strlen($str) > 200)&&(strpos($str,'.')))// если есть точка, и строка длинная, то минимально порежем конец имени файла
	{
		$nameExt=explode('.', $str);
		$name = $nameExt[0];
		$extension = $nameExt[1];
		$name = substr($name, 0, 200-strlen($extension)-1);
		$str = $name.".".$extension;	
	}else{
		$str = preg_replace('/(.{200}).*/', '$1', $str);//раз в названии нет точек, то отрезать все, что за пределами дозволенных 200 символов от начала
	}	
	echo $str.'<br>';
}
