<?php
//include_once 'task4_ex1_EPAM_config.php';
//phpinfo();
session_start();
ob_start();
//показывает форму для входа
function showEntryForm()
{
	echo <<<HEREDOC
	<form>
	<p> Логин  <INPUT NAME= "login"> <br>
      	Пароль  <INPUT NAME= "password"> <br> 
      	<INPUT TYPE=SUBMIT target = 'task4_ex1_EPAM.php'>
    </p>
	</form>
HEREDOC;
}
// показывает ссылку для логаута
function showLogoutButton()
{
	echo <<<HEREDOC
	<p> <a href = task4_ex1_EPAM.php?logout>Выход</a></p>
HEREDOC;
}
//показывает форму для загрузки файлов
function showUploadForm()
{
	echo <<<HEREDOC
	<form action="task4_ex1_EPAM.php" method="post" enctype="multipart/form-data">
	<p>
      <input type="file" name="filename"><br> 
      <input type="submit" value="Загрузить"><br>
    </p>
	</form>
HEREDOC;
}


//проверка на запрос о выходе
if (isset($_GET['logout']))
{
session_start();
session_unset();
}
//показывает форму для закачки
function showDownloadForm()
{
	echo <<<HEREDOC
	<form>
	<p> ссылка для скачивания  <INPUT NAME= "downloadLink"> <br>
      	<INPUT TYPE=SUBMIT target = 'task4_ex1_EPAM.php'>
    </p>
	</form>
HEREDOC;
}
//показывает разрешенные типы файлов 
function showUserData()
{
	//echo "<p>Разрешены форматы файлов ". $_SESSION['allowedFormats'] ."</p>";
	echo "<p>Общий доступный объем загрузок ". $_SESSION['limitTotal'] ."</p>";
	echo "<p>Домашняя директория ". $_SESSION['homeDirectory'] ."</p>";
}
//показывает файлы в домашней директории и кнопки для их удаления
function showHomeDirFiles()
{
	$files = scandir($_SESSION['homeDirectory']);
	echo <<<HEREDOC
	<form action="task4_ex1_EPAM.php" method="post" id="deleteForm">
	<p>
HEREDOC;
     
	foreach ($files as $file) 
	{
		if ($file !='.' && $file !='..')
		{
			echo '<a href = ' . $_SESSION['homeDirectory'] . '/' . $file . ' target="_blank" >'. $file . '</a><button name ="Download" type="submit"  value='. $file . ' >скачать</button></a>'. '<a><button name ="Delete" type="submit"  value='. $file . ' >удалить</button></a><br>';
		}
	}
	echo "</p></form>";
	
}
//скрипт удаления файлов
if ($_POST['Delete'] !='')
{
	unlink($_SESSION['homeDirectory']. '/'.$_POST['Delete']);
}

//скрипт скачки файлов
if ($_POST['Download'] !='')
{	
	//ob_clean();
    //flush();
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="' . $_POST['Download'] . '";');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	ob_clean();
    flush();
    readfile($_SESSION['homeDirectory']. '/'.$_POST['Download']);
     
}

//получение ссылки для скачивания файла и скачивание файла
if (isset($_GET))
{
	$downloadLink = $_GET['downloadLink'];
	 //curl
		$ch = curl_init($downloadLink);
	    $curl_options = array(
                            CURLOPT_RETURNTRANSFER => 1,
                            CURLOPT_REFERER        => "http://referer.html",
                            CURLOPT_FAILONERROR    => 1,
                            CURLOPT_USERAGENT      => "Opera/10.00 (Windows NT 6.0; U; ru)",
                            CURLOPT_HEADER         => 0,// тут было 1
                            CURLOPT_TIMEOUT        => 240
                         );
    	curl_setopt_array($ch, $curl_options);
		$result = curl_exec($ch);
        curl_close($ch);
        if ($result)
        {
        	/*
        	//загрузка с вызовом окна скачивания
        	header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename="' . basename($downloadLink) . '";');
		   	header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    ob_clean();
        	flush();
		    echo $result;
		    
		    exit();
 			*/
 		$fileNameToSaveUnder = basename($downloadLink);
 		file_put_contents($_SESSION['homeDirectory'] . '/' . $fileNameToSaveUnder, $result);
        }

}

//определение того, какие элементы страницы показать
function chooseElements()
{
	if ($_SESSION['loggedIn'] == '1')
	{
	showLogoutButton();
	showUserData();
	showHomeDirFiles();
	showDownloadForm();
	showUploadForm();
	}
	else
	{
	showEntryForm();
	}
}

//опредеяет суммарный объем файлов пользователя
function getSizeOfFiles()
{
$totalFileSize = 0;
$files = scandir($_SESSION['homeDirectory']);
foreach($files as $file)
	{
		$totalFileSize += filesize($_SESSION['homeDirectory'].'/'.$file);
	}
return $totalFileSize;
}


//скрипт загрузки файла на сайт с машины пользователя
if ($_FILES)
{
	$filesize = $_FILES["filename"]["size"];
	//получение расширения файла и проверка допустимости
	$filename = explode('.', $_FILES["filename"]["name"]);
	if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
	{
		if (!file_exists($_SESSION['homeDirectory']))
		{
			mkdir($_SESSION['homeDirectory']);
		} 
		$fileTypeSizes = allowedFileTypesSizes("login_data.xml", $_SESSION['login']);
		foreach ($fileTypeSizes as $fileType => $fileTypeSize)
		{
			//echo $fileType . '=>'. $fileTypeSize;
			//echo $filename[1];
			if (($filesize < $fileTypeSize*1024*1024) && ($filename[1] == $fileType))
			{
				move_uploaded_file($_FILES["filename"]["tmp_name"], $_SESSION['homeDirectory'] . '/' .$_FILES["filename"]["name"]);	
			}
		}
	}
	else
	{
		echo("Ошибка загрузки файла");
	}	
}

//получение допустимых расширений и их размеров из xml для данного пользователя
function allowedFileTypesSizes($xmlFile, $login)
{
	$userData = simplexml_load_file($xmlFile);
	foreach ($userData as $user)
	{
		$xml_login =  (string)$user -> login;
		if ($xml_login == $login)
		{
			$extSize = array();	
			foreach ($user -> allowed -> extension as $extension)
			{
				$extensionName = (string)$extension -> name;
				$extensionSizeAllowed = (string)$extension -> size;
				$extSize[$extensionName] = $extensionSizeAllowed;
				
			}
		}
	}
return $extSize;
}
//print_r(allowedFileTypesSizes("login_data.xml", 'login'));

//проверка логина и пароля
if (isset($_GET))
{
	$login = $_GET['login'];
	$pass = $_GET['password'];
	//подключение *.xml с данными пользователей
	$userData = simplexml_load_file("login_data.xml");
	//перебор пользователей
	foreach ($userData as $user)
	{
		$xml_login =  (string)$user -> login;
		$xml_password = $user -> password;
		//$allowedFormats = (string)$user -> allowed;
		$homeDirectory = (string)$user -> home;
		$limitTotal = (string)$user -> totalLimit;
		
		if ($xml_password == $pass && $xml_login == $login)
		{
			$_SESSION['loggedIn'] = '1';
			$_SESSION['login'] = $xml_login;
			//$_SESSION['allowedFormats'] = $allowedFormats;
			$_SESSION['homeDirectory'] = $homeDirectory;
			$_SESSION['limitTotal'] = $limitTotal;
			
		}
	}
}
chooseElements();
?>