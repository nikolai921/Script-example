<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

session_start();
function redir(){
	header('Location: /personalyArea.php');
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

var_dump($_SESSION);



echo '<a href="/authorization.php">Authorized</a>' . '</br>';

$title='Insert';


$id = $_SESSION['id'];
$query = "SELECT * FROM users WHERE id='$id'";
$result = mysqli_query($link, $query) or die(mysqli_error($link));
$row = mysqli_fetch_assoc($result);

function correctDataEntry($row){

$id = $_SESSION['id'];

//Устанавливаем доступы к базе данных:
$host = 'localhost'; //имя хоста, на локальном компьютере это localhost
$user = 'root'; //имя пользователя, по умолчанию это root
$password = ''; //пароль, по умолчанию пустой
$db_name = 'dicisionSQL'; //имя базы данных

//Соединяемся с базой данных используя наши доступы:
$link = mysqli_connect($host, $user, $password, $db_name);

//Устанавливаем кодировку (не обязательно, но поможет избежать проблем):
mysqli_query($link, "SET NAMES 'utf8'");


	$inputResult = [];
	$correct = 1;

		$login = mysqli_real_escape_string($link, $_POST['login']);
		if(preg_match('#^[a-zA-Z0-9]{4,10}$#', $login) != 1){
		$inputResult['LoginEnteredInform'] = 'Login is incorrect';
		$correct *= 0;
		} else {
			$inputResult['LoginEnteredInform'] = '';
		}

		$e_mail = mysqli_real_escape_string($link, $_POST['e_mail']);
		if(preg_match('#^[a-zA-Z-.]+@[a-z]+\.[a-z]{2,3}$#', $e_mail) != 1){
		$inputResult['e_mailEnteredInform'] = 'e_mail is incorrect';
		$correct *= 0;
		} else {
			$inputResult['e_mailEnteredInform'] = '';
		}

		$birthday = mysqli_real_escape_string($link, $_POST['birthday']);
		if(preg_match('#^(?:[0-2][0-9]|3[0-1])\.(?:0[0-9]|1[0-2])\.[0-9]{4,}$#', $birthday) == 1){
		$birthdaySend = date('Y-m-d', strtotime(mysqli_real_escape_string($link, $_POST['birthday'])));
		$inputResult['BirthdayEnteredInform'] = '';
		} else {
			$inputResult['BirthdayEnteredInform'] = 'birthday date is incorrect';
			$correct *= 0;
			}

		$country = mysqli_real_escape_string($link, $_POST['country']);
		$inputResult['country'] = $country;

		$dateNow = date('Y-m-d', time());
		$inputResult['dateNow'] = $dateNow;

	if($correct == 1){
	$query = "UPDATE users SET login='$login', e_mail='$e_mail', birthday='$birthdaySend', dateNow='$dateNow', country='$country' WHERE id='$id'";
		$result = mysqli_query($link, $query) or die(mysqli_error($link));

		$_SESSION['login'] = $login;
		$inputResult['row'] = $row;
		$inputResult['id'] = $id;
	}


	$inputResult['correct'] = $correct;

	return $inputResult;

}




if(!empty($_POST['password']) and !empty($_POST['login']) and !empty($_POST['confirm'])){

formSend($dataInform, $title, $row);
$dataInform = correctDataEntry($row);
} else {
	formSend($dataInform = 0, $title, $row);
}



var_dump($_POST);
var_dump(correctDataEntry($row));



function formSend($dataInform, $title, $row){

if($row){$loginPost = $row['login'];} else {$loginPost = '';}
if($row){$e_mailPost = $row['e_mail'];} else {$e_mailPost = '';}
if($row){$birthdayPost = date('d.m.Y', strtotime($row['birthday']));} else {$birthdayPost = '';}



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
						<br>old password:<br>
		 				<p><input name="password" type="password"></p>
						'.$dataInform['PasswordEnteredInform'].'
		 				<br>new password:<br>
		 				<p><input name="password" type="password"></p>
						'.$dataInform['PasswordEnteredInform'].'
						<br>repeat password:<br>
		 				<p><input name="confirm" type="password"></p><br>
		 				'.$dataInform['confirm'].'
		 				<p><input type="submit" class="btn btn-info btn-block" value="Changed password"></p>
		 				</form>

						<form action="" method="POST">
		 				<br>login:<br>
						<p><input name="login" Value="'.$loginPost.'"></p>
						'.$dataInform['LoginEnteredInform'].' <> '.$dataInform['LoginExists'].'
						<br>e_mail:<br>
						<p><input name="e_mail" Value="'.$e_mailPost.'"></p>
						'.$dataInform['e_mailEnteredInform'].'
						<br>birthday:<br>
						<p><input name="birthday" Value="'.$birthdayPost.'"></p>
						'.$dataInform['BirthdayEnteredInform'].'
						<br>country:<br>
						<p><select name="country">
							<option>Russia</option>
							<option>Belarus</option>
							<option>Kazakhstan</option>
							<option>England</option>
						</select></p><br>

		 				<p><input type="submit" class="btn btn-info btn-block" value="Update"></p>
	 				</form>
				</header>
			</div>
		</body>
		<title> '.$title.' </title>
	</html>';

}

redir();
