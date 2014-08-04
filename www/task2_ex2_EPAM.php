<?php
#остается решить как менять глубину резания (до второго, третьего и т.д. знака)

ini_set('max_execution_time', 60); 
$fileaddress = 'randomtext.txt';
$sortedFile = 'sortedrandomtext.txt.';
$workdir = 'slices';
sliceBigFile($fileaddress,$workdir);
secondslice($workdir);
//$memoryAvailable = ini_get('memory_limit');
$memoryAvailable = 1000;

function sliceBigFile($fileaddress, $workdir, $indletter=1)
{
$fForRead = fopen($fileaddress, 'r');
	if ($fForRead)
	{
		while (!feof($fForRead))
		{
			$mytext = fgets($fForRead);
			//создать файл на каждую букву алфавита
			$indexLetter = substr($mytext, 0, $indletter);
			if ($mytext[0] != '')
			{
				$fileName = strtolower($indexLetter) . '.txt';
				$writeTo = $workdir.'/'.$fileName;
				$fileForChunk = fopen($writeTo, 'a+');
				echo "запись в файл $writeTo </br>";
				fwrite($fileForChunk, $mytext);
			}
		}
	}
	else echo "Ошибка при открытии файла";
	fclose($fForRead);
}

//проверяем размер файлов в папке и режем их

function secondSlice($workdir)
{
$files = scandir($workdir);
	foreach($files as $file) 
	{
		if ($file != '.' && $file != '..')
		{	
			$file_Size = filesize($workdir.'/'.$file);
			echo "размер файла " . $workdir.'/'.$file . ' ' . $file_Size . '<br/>';
			if ($file_Size >= $memoryAvailable)
			{
				sliceBigFile($workdir . '/' . $file, $workdir, 2);
				unlink($workdir . '/' . $file);
			}
		}
	}
}
//сортируем каждый файл в папке
$files = scandir($workdir);
foreach($files as $file) 
{
	if ($file != '.' && $file != '..')
	{	
		$writeTo = $workdir.'/'.$file;
		$data = file($writeTo);
		//print_r($data);
		sort($data);
		file_put_contents($writeTo, implode($data));
	}
}
//собираем отсортированные файлы
sort($files);
$fileForReady = fopen($sortedFile, 'a');
foreach($files as $file)
{
	if ($file != '.' && $file != '..')
	{
		$writeFromAddress = $workdir.'/'.$file;
		$writeFrom = fopen($writeFromAddress, 'r');
		while (!feof($writeFrom))
		{
		$line = fgets($writeFrom);
   		fwrite($fileForReady, $line);
		}

	}
}

	
