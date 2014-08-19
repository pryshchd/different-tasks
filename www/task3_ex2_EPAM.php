<?php
include_once 'task3_ex1_EPAM_config.php';
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
      	<INPUT TYPE=SUBMIT target = 'task3_ex2_EPAM.php'>
    </p>
	</form>
HEREDOC;
}
// показывает ссылку для логаута
function showLogoutButton()
{
	echo <<<HEREDOC
	<p> <a href = task3_ex2_EPAM.php?logout>Выход</a></p>
HEREDOC;
}
//проверка на запрос о выходе
if (isset($_GET['logout']))
{
session_start();
session_unset();
}
//показывает форму для закачки
function showDoWnloadForm()
{
	echo <<<HEREDOC
	<form>
	<p> ссылка для скачивания  <INPUT NAME= "downloadLink"> <br>
      	<INPUT TYPE=SUBMIT target = 'task3_ex2_EPAM.php'>
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
        	//ob_clean();
        	//flush();
        	header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename="' . basename($downloadLink) . '";');
		   	header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    //header('Content-Length: ' . filesize($downloadLink));
		    ob_clean();
        	flush();
		    echo $result;
		    
		    exit();
 
        }
	/*
	if (file_exists($downloadLink)) 
	{		
		//заголовки и загрузка через readfile
		header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    //header('Content-Disposition: attachment; filename="' . basename($downloadLink) . '";');
	   	header('Content-Disposition: attachment; filename=' . $downloadLink );
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($downloadLink));
	    ob_clean();
        flush();
	    readfile($downloadLink);
	    exit;		
	}
	*/
}

//определение того, какие элементы страницы показать
function chooseElements()
{
	if ($_SESSION['loggedIn'] == '1')
	{
	showLogoutButton();
	showDoWnloadForm();
	}
	else
	{
	showEntryForm();
	}
}

//проверка логина и пароля
if (isset($_GET))
{
	$login = $_GET['login'];
	$pass = $_GET['password'];
	if ($users[$login]['password'] == $pass && $pass != '')
	{
		$_SESSION['loggedIn'] = '1';
		chooseElements();
	}
	else 
	{
		chooseElements();
	}
}
?>