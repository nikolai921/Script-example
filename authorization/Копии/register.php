<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

session_start();

echo '<a href="/authorization.php">Authorized</a>' . '</br>';

$title='Insert';

function correctDataEntry(){

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

		if($_POST['password'] != $_POST['confirm']){
			$inputResult['confirm'] = 'Password is not correct. Password vs Confirm';
			$correct *= 0;
		} else {
			$inputResult['confirm'] = '';
		}

		$login = mysqli_real_escape_string($link, $_POST['login']);
		if(preg_match('#^[a-zA-Z0-9]{4,10}$#', $login) != 1){
		$inputResult['LoginEnteredInform'] = 'Login is incorrect';
		$correct *= 0;
		} else {
			$inputResult['LoginEnteredInform'] = '';
		}

		$password = mysqli_real_escape_string($link, $_POST['password']);
		if(preg_match('#^[a-zA-Z0-9]{6,12}$#', $password) != 1){
		$inputResult['PasswordEnteredInform'] = 'Password is incorrect. Format password';
		$correct *= 0;
		} else {
			$passwordHash = password_hash($password, PASSWORD_DEFAULT);
			$inputResult['PasswordEnteredInform'] = '';
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

	$query = "SELECT * FROM users WHERE login='$login'";
	$result = mysqli_query($link, $query) or die(mysqli_error($link));
	$row = mysqli_fetch_assoc($result);
	if($correct == 0){
	$inputResult['LoginExists'] = '';
	} elseif($row == false){
		$query = "INSERT INTO users SET password='$passwordHash', login='$login', e_mail='$e_mail', birthday='$birthdaySend', dateNow='$dateNow', country='$country'";
		$result = mysqli_query($link, $query) or die(mysqli_error($link));

		$_SESSION['auth'] = true;

		$id = mysqli_insert_id($link);

		$_SESSION['id'] = $id;
		$inputResult['id'] = $id;
		$inputResult['LoginExists'] = '';
	} else {
		$inputResult['LoginExists'] = 'login is exists';
	}

	$inputResult['correct'] = $correct;

	return $inputResult;
}



if(!empty($_POST['password']) and !empty($_POST['login']) and !empty($_POST['confirm'])){

$dataInform = correctDataEntry();
formSend($dataInform, $title);

} else {
	formSend($dataInform = 0, $title);
}



var_dump($_SESSION);
var_dump($_POST);
var_dump($dataInform);



function formSend($dataInform, $title){

if($_POST){$loginPost = $_POST['login'];} else {$loginPost = '';}
if($_POST){$e_mailPost = $_POST['e_mail'];} else {$e_mailPost = '';}
if($_POST){$birthdayPost = $_POST['birthday'];} else {$birthdayPost = '';}

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
						'.$dataInform['PasswordEnteredInform'].'
						<br>repeat password:<br>
		 				<p><input name="confirm" type="password"></p>
						'.$dataInform['confirm'].'
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

		 				<p><input type="submit" class="btn btn-info btn-block" value="Insert"></p>
	 				</form>
				</header>
			</div>
		</body>
		<title> '.$title.' </title>
	</html>';

}







									// блок для расспаковки массива с информацией о регистрации

// $arrayInform['correct'] = $correct;
// $arrayInform['row'] = $row;
// $arrayInform['inputResult'] = $inputResult;

// $arrayData = correctDataEntry();

// 	foreach($arrayData as $elem){
// 		switch($elem){
// 		case "row":
// 				$row = $elem;
// 		case "inputResult":
// 				$inputResult = $elem;
// 		case "correct":
// 				$correct = $elem;
// 		case "id":
// 				$id = $elem;
// 				break;
// 			}
// 	}









// проводится проверка на наличие переданой статьй параметром POST, в БД. что бы избежать повторения статей.
// if(!empty($_POST['password']) and !empty($_POST['login'])){
// 	$password = mysqli_real_escape_string($link, $_POST['password']);
// 	$login = mysqli_real_escape_string($link, $_POST['login']);


// 	$query = "SELECT COUNT(*) as count  FROM users WHERE password='$password'";
// 	$result = mysqli_query($link, $query) or die(mysqli_error($link));
// 	$row = mysqli_fetch_assoc($result)['count'];

// 	if($row){
// 		echo 'Page with this url exsist';
// 	} else {
// 		$query = "INSERT INTO users SET password='$password', login='$login'";
// 			if(mysqli_query($link, $query) == true){
// 				echo 'Page add seccessfully';
// 				} else {
// 					die(mysqli_error($link));
// 				}

// 			}
// }


// function gettingCategory(){

// 	echo '<form action="" method="POST">
// 			<br>password:<br>
// 			<p><input name="password"></p>
// 			<br>login:<br>
// 			<p><input name="login"></p>

// 			<p><input type="submit" class="btn btn-info btn-block" value="Сохранить"></p>
// 		</form>';

// }



// gettingCategory();





