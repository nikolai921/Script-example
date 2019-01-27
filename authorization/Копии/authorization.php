<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

session_start();

function redir(/main.php){
	header('Location: /main.php');
}


//Устанавливаем доступы к базе данных:
$host = 'localhost'; //имя хоста, на локальном компьютере это localhost
$user = 'root'; //имя пользователя, по умолчанию это root
$password = ''; //пароль, по умолчанию пустой
$db_name = 'dicisionSQL'; //имя базы данных

//Соединяемся с базой данных используя наши доступы:
$link = mysqli_connect($host, $user, $password, $db_name);

//Устанавливаем кодировку (не обязательно, но поможет избежать проблем):
mysqli_query($link, "SET NAMES 'utf8'");

if(!empty($_SESSION['login'])){
	$title = $_SESSION['login'];
} else {
	$title = 'aut';
}

if(!empty($_POST['password']) and !empty($_POST['login'])){
	$password = mysqli_real_escape_string($link, $_POST['password']);
	$login = mysqli_real_escape_string($link, $_POST['login']);
	$_SESSION['login'] = $login;

	$query = "SELECT * FROM users WHERE login='$login'";
	$result = mysqli_query($link, $query) or die(mysqli_error($link));
	$row = mysqli_fetch_assoc($result);

	if($row){
		$passwordHash = $row['password'];
		if(password_verify($password, $passwordHash)){
			redir('/main.php');
			$_SESSION['auth'] = true;
			$_SESSION['id'] = $row['id'];
			}
		} else {
		echo 'User is not authorized';

		echo formAuthorize($title);
	}
} else {

	echo formAuthorize($title);
}

echo '<a href="/register.php">Register</a>' . '</br>';
echo '<a href="/number1.php">number1</a>' . '</br>';
echo '<a href="/users.php">List of users</a>' . '</br>';

var_dump($_SESSION);

function formAuthorize ($title){
echo '<!DOCTYPE html>
	<html lang="ru">
		<head>
			<meta charset="utf-8">
			<link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css">
			<link rel="stylesheet" href="css/bootstrap/css/bootstrap.css">
			<link rel="stylesheet" href="css/styles.css">
		</head>
		<body>
			<div id="wrapper">
				<header>
						<form action="" method="POST">
		 				<br>password:<br>
		 				<p><input name="password" type="password"></p>
		 				<br>login:<br>
						<p><input name="login"></p>

		 				<p><input type="submit" class="btn btn-info btn-block" value="Send"></p>
	 				</form>
				</header>
			</div>
		</body>
		<title> '.$title.' </title>
	</html>';
}

