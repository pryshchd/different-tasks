<?php
include_once 'task3_ex1_EPAM_config.php';
session_start();
//проверка на запрос о выходе
if (isset($_GET['logout']))
{
session_start();
session_unset();
}
//показывает форму для загрузки файлов
function showUploadForm()
{
	echo <<<HEREDOC
	<form action="task3_ex1_EPAM.php" method="post" enctype="multipart/form-data">
	<p>
      <input type="file" name="filename"><br> 
      <input type="submit" value="Загрузить"><br>
    </p>
	</form>
HEREDOC;
}
//показывает форму для логина
function showEntryForm()
{
	echo <<<HEREDOC
	<form>
	<p> Логин  <INPUT NAME= "login"> <br>
      	Пароль  <INPUT NAME= "password"> <br> 
      	<INPUT TYPE=SUBMIT target = 'task3_ex1_EPAM.php'>
    </p>
	</form>
HEREDOC;
}
// показывает ссылку для логаута
function showLogoutButton()
{
	echo <<<HEREDOC
	<p> <a href = task3_ex1_EPAM.php?logout>Выход</a></p>
HEREDOC;
}

//показывает файлы в домашней директории и кнопки для их удаления
function showHomeDirFiles()
{
	$files = scandir($_SESSION['homeDir']);
	echo <<<HEREDOC
	<form action="task3_ex1_EPAM.php" method="post" id="deleteForm">
	<p>
HEREDOC;
     
	foreach ($files as $file) 
	{
		if ($file !='.' && $file !='..')
		{
			echo '<a href = ' . $_SESSION['homeDir'] . '/' . $file . '>'. $file . '</a><button name ="Delete" type="submit"  value='. $file . ' >удалить</button><br>';
		}
	}
	echo "</p></form>";
	
}

//определение того, какие элементы страницы показать
function chooseElements()
{
	if ($_SESSION['loggedIn'] == '1')
	{
	showLogoutButton();
	showUploadForm();
	showHomeDirFiles();
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
$files = scandir($_SESSION['homeDir']);
//print_r($files);
//echo $_SESSION['homeDir'];
foreach($files as $file)
	{
		$totalFileSize += filesize($_SESSION['homeDir'].'/'.$file);
	}
return $totalFileSize;
}


//скрипт загрузки файла
if ($_FILES)
{
	$filesize = $_FILES["filename"]["size"];
	//получение расщирения файла и проверка допустимости
	$filename = explode('.', $_FILES["filename"]["name"]);
	$userLogin = $_SESSION['login'];
	$allowedTypes = ($users[$userLogin]['allowed']);
	//print_r($allowedTypes);
	foreach ($allowedTypes as $type => $sizeAllowed)
	{
		if ($filename[1] == $type)
		{
			$allowedSizeForFilesOfThisType = $sizeAllowed;
			$allowedSizeForFilesOfThisType;
		}
	}
	
	
	//проверка размера

	if($filesize > 1024*1024*$_SESSION['limit'])
	{
    	echo ("Размер файла превышает общий допустимый для данного пользователя");
     	exit;
    }
    if (getSizeOfFiles()+$filesize > 1024*1024*$_SESSION['limit'])
    {
     	echo ("заргузка файла невозможна: ваш лимит будет превышен");
     	//echo $_SESSION['limit'];
     	exit;
	}
	if ($filesize > 1024*1024*$allowedSizeForFilesOfThisType)
	{
		echo ("заргузка файла невозможна: размер превышает допустимый для файлов такого типа");
     	exit;
	}	
	if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
	{
		if (!file_exists($_SESSION['homeDir']))
		{
			mkdir($_SESSION['homeDir']);
		}  	
		move_uploaded_file($_FILES["filename"]["tmp_name"], $_SESSION['homeDir'] . '/' .$_FILES["filename"]["name"]);
	}
	else
	{
		echo("Ошибка загрузки файла");
	}
}	

//скрипт удаления файлов
if ($_POST['Delete'] !='')
{
	unlink($_SESSION['homeDir']. '/'.$_POST['Delete']);
}

//проверка логина и пароля
if (isset($_GET))
{
	$login = $_GET['login'];
	$pass = $_GET['password'];
	if ($users[$login]['password'] == $pass && $pass != '')
	{
		$_SESSION['loggedIn'] = '1';
		$_SESSION['homeDir'] = $users[$login]['home'];
		$_SESSION['limit'] = $users[$login]['limit'];
		$_SESSION['login'] = $_GET['login']; 
		chooseElements();
	}
	else 
	{
		chooseElements();
	}
}

//для дебага
//echo 'GET ';
//print_r($_GET);
//echo "<br/>";
//echo 'POST ';
//print_r($_POST);
//echo "<br/>";
//echo 'SESSION ';
//print_r($_SESSION);
//echo "<br/>";
//echo getSizeOfFiles();
?>