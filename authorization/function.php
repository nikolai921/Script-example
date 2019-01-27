<?php
				// GENERAL FUNCTION BLOCK
				// БЛОК ОБЩИХ ФУНКЦИЙ

											// TITLE function connection with database start Sessions, main error controllers
											// Функция ЗАГОЛОВОК соединение с БД старт Сессии, контролеры основных ошибок

error_reporting(E_ALL);
ini_set('display_errors', 'on');

session_start();
//Устанавливаем доступы к базе данных:
$host = 'localhost'; //имя хоста, на локальном компьютере это localhost
$user = 'root'; //имя пользователя, по умолчанию это root
$password = ''; //пароль, по умолчанию пустой
$db_name = 'dicisionSQL'; //имя базы данных

//Соединяемся с базой данных используя наши доступы:
$link = mysqli_connect($host, $user, $password, $db_name);

//Устанавливаем кодировку (не обязательно, но поможет избежать проблем):
mysqli_query($link, "SET NAMES 'utf8'");

											// Redirect function
											// Функция редиректа

function redir ($addres){
	header('Location: '.$addres.'');
}
											// Link function with variables: Address and Name
											// Функция ссылки с перменными: Адресс и Наимнование

function newLink ($address, $name)
{

echo '<a href="'.$address.'">'.$name.'</a>';

}

											// Header for pages with user name and status
											// Заголовок для страниц с именем и статусом пользователя

function headerUser ($address, $name){

if(isset($_SESSION['auth'])){
	echo '<!DOCTYPE html>
		<html lang="ru">
			<head>
				<meta charset="utf-8">
				<link rel="stylesheet" href="../css/bootstrap/css/bootstrap.css">
				<link rel="stylesheet" href="css/bootstrap/css/bootstrap.css">
				<link rel="stylesheet" href="css/styles.css">
				'.$_SESSION['login'].' - status - '.$_SESSION['status'].' <br> '.newLink ($address, $name).'
			</head>
			<body>
				<div id="wrapper">
					<header>

					</header>
				</div>
			</body>
			<title> '.$_SESSION['login'].' </title>
		</html>';
	} else {
	echo 'you are not authorized';
	echo '</br>';

	}
}

											// Getting Status
											// Получение статуса пользователя

function getStatusUser($elem){

	// $login = $_SESSION['login'];

	// $query = "SELECT *, status.name as status FROM users LEFT JOIN status ON users.status_id = status.id WHERE login='$login'";
	// 		$result = mysqli_query($link, $query) or die(mysqli_error($link));
	// 		$arrayStatus = mysqli_fetch_assoc($result);
	// 		if($arrayStatus['status_id'] == 1){
	// 			$status = 'admin';
	// 		} else {
	// 			$status = 'user';
	// 		}

		if($elem == 1) {
			$elem = 'admin';
		} else {
			$elem = 'user';
		}
return $elem;
}


				// AUTHORIZATION UNIT
				// БЛОК АВТОРИЗАЦИИ

											// Authorization unit
											// Блок Авторизации

function authorizationUnit ($link, $title){
	if(!empty($_POST['password']) and !empty($_POST['login'])){

			$password = mysqli_real_escape_string($link, $_POST['password']);
			$login = mysqli_real_escape_string($link, $_POST['login']);
			$_SESSION['login'] = $login;

			$query = "SELECT * FROM users WHERE login='$login'";
			$result = mysqli_query($link, $query) or die(mysqli_error($link));
			$row = mysqli_fetch_assoc($result);


			if($row){
				$passwordHash = $row['password'];
				if(password_verify($password, $passwordHash) and $row['banned'] == 0){
					redir('/main.php');
					$_SESSION['status'] = getStatusUser($row['status_id']);
					$_SESSION['auth'] = true;
					$_SESSION['id'] = $row['id'];
					$_SESSION['banned'] = $row['banned'];

						} else {
						echo 'User is not authorized';
						echo formAuthorization($title);
					}
			}

	} else {
		echo formAuthorization($title);
	}
// return $row;
}

											// Authorization form
											// Фома Авторизации

function formAuthorization ($title){
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


					// REGISTRATION BLOCK
					// БЛОК РЕГИСТРАЦИИ

											// Check registration data and add new user to BD
											// Проверка данных регистрации и добавление новго пользователя в BD

function correctDataEntry($link){

	$inputResult = [];
	$correct = 1;

		if(md5($_POST['password']) != md5($_POST['confirm'])){
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

		$status = 2;

	$query = "SELECT * FROM users WHERE login='$login'";
	$result = mysqli_query($link, $query) or die(mysqli_error($link));
	$row = mysqli_fetch_assoc($result);
	if($correct == 0){
	$inputResult['LoginExists'] = '';
	} elseif($row == false){
		$query = "INSERT INTO users SET password='$passwordHash', login='$login', e_mail='$e_mail', birthday='$birthdaySend', dateNow='$dateNow', country='$country', status_id='$status', banned='0'";
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

											// Registration form
											// Фома Регистрации

function formDataSend($dataInform, $title){

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


				// BLOCK User table
				// БЛОК Таблица пользователей

											// Users table
											// Таблица пользователей


function usersTable($link){

$query = "SELECT * FROM users";
$result = mysqli_query($link, $query) or die(mysqli_error($link));
for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

var_dump($data);

$userTable = '<table>
			<tr>
				<th>Login</th>
				<th>Profile</th>
			</tr>';

		foreach($data as $elem){
			$userTable .= "<tr>
				<td>{$elem['login']}</td>
				<td><a href=\"../profile.php?loginProfile={$elem['id']}\">Profile</a></td>
			</tr>";
		}
		$userTable .= '</table>';

		echo $userTable;
	}


				// Custom Data Description LOCK
				// БЛОК описания пользовательских данных

											// Function of counting age by date of birth
											// Функция подсчета возроста по дате рождения


function calculate_age($birthday){

	$birthday_timestamp = strtotime($birthday);
	$age = date('Y') - date('Y', $birthday_timestamp);
	if(date('md', $birthday_timestamp) > date('md')){
		$age--;
	}

	return $age;

}

											// Таблица 'Профиль пользователя'
											// Table 'User Profile'

function userProfile($data){

$age = calculate_age($data[0]['birthday']);

$userProfile = '<table>
			<tr>
				<th>Login</th>
				<th>Birthday</th>
				<th>Check in</th>
				<th>Country</th>
			</tr>';

		foreach($data as $elem){
			$userProfile .= "<tr>
				<td>{$elem['login']}</td>
				<td>$age</td>
				<td>{$elem['dateNow']}</td>
				<td>{$elem['country']}</td>
			</tr>";
		}
		$userProfile .= '</table>';

		echo $userProfile;
}


				// Description block to edit user data
				// БЛОК описания редактирования пользовательских данных

											// Checking the correctness of changed data sent to the DB
											// Проверка коректности измененых данных с отправкой в БД

function correctChangeData($link, $row){

$id = $_SESSION['id'];

	$inputResult = [];
	$correct = 1;

		$login = mysqli_real_escape_string($link, $_POST['login']);
		if(preg_match('#^[a-zA-Z0-9]{4,10}$#', $login) != 1){
		$inputResult['LoginEnteredInform'] = 'Login is incorrect';
		$correct *= 0;
		} else {
			$inputResult['LoginEnteredInform'] = '';
		}

		$query = "SELECT * FROM users WHERE login='$login' and id!='$id'";
		$result = mysqli_query($link, $query) or die(mysqli_error($link));
		$avialabilityLogin = mysqli_fetch_assoc($result);
		if($avialabilityLogin){
		$inputResult['LoginExists'] = 'Login is exists';
		$correct *= 0;
		} else {
			$inputResult['LoginExists'] = '';
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

											// Checking the correctness of the changed password by sending to the DB
											// Проверка коректности измененых пароля по отправки в БД

function correctPasswordData($link, $row){

$id = $_SESSION['id'];

	$inputResult = [];
	$correct = 1;

		$oldPasswordHash = $row['password'];
		if(password_verify($_POST['old_password'], $oldPasswordHash)){

				if($_POST['password'] != $_POST['confirm']){
					$inputResult['confirm'] = 'Password is not correct. Password vs Confirm';
					$correct *= 0;
				} else {
					$inputResult['confirm'] = '';
				}

				$password = mysqli_real_escape_string($link, $_POST['password']);
				if(preg_match('#^[a-zA-Z0-9]{6,12}$#', $password) != 1){
				$inputResult['PasswordEnteredInform'] = 'Password is incorrect. Format password';
				$correct *= 0;
				} else {
					$passwordHash = password_hash($password, PASSWORD_DEFAULT);
					$inputResult['PasswordEnteredInform'] = '';
				}

				$dateNow = date('Y-m-d', time());
				$inputResult['dateNow'] = $dateNow;

			if($correct == 1){
			$query = "UPDATE users SET password='$passwordHash', dateNow='$dateNow' WHERE id='$id'";
				$result = mysqli_query($link, $query) or die(mysqli_error($link));

				$inputResult['id'] = $id;
				$inputResult['OldPasswordCorrect'] = 'Old Password Matches';
				}
		} else {
		$inputResult['OldPasswordCorrect'] = 'OldPasswordEnteredIncorrectly';
		}

	$inputResult['correct'] = $correct;

	return $inputResult;

}

											// User change form
											// Форма изменения данных пользователя

function formChangeData($dataInform, $title, $row){

// this is an array of resulting data after the implementation of the function correctChangData ($ link, $ row)
// $dataInform это массив результрущих данных после реализации функции correctChangData($link, $row)

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
		 				<p><input name="old_password" type="password"></p>
						'.$dataInform['OldPasswordCorrect'].'

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

											// Account Removal Request Form
											// Форма запроса на удаление аккаунта

function formPageDelet($dataInform, $title){

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
		 				<p><input name="delet_password" type="password"></p>
						'.$dataInform['Delet_page'].'

						<br>
						<p><input type="submit" class="btn btn-info btn-block" value="Delete page"></p>
		 			</form>
				</header>
			</div>
		</body>
	</html>';

}

											// Delete user account
											// Удаление аакаунта позьзователя

function userPageDelet($link, $row){

$id = $_SESSION['id'];

	$inputResult = [];
	$correct = 1;

		$oldPasswordHash = $row['password'];
		if(password_verify($_POST['delet_password'], $oldPasswordHash)){

			$query = "DELETE FROM users WHERE id='$id'";
			$result = mysqli_query($link, $query) or die(mysqli_error($link));

				session_destroy();
				// redir('/main.php');

		}

	$inputResult['correct'] = $correct;

	return $inputResult;

}

											// Список пользователей (Админа)
											// User list (Admin)

function userListAdmin($data, $statusBan){

$id = $_SESSION['id'];



$userList = '<table>
			<tr>
				<th>Login</th>
				<th>Status</th>
				<th>Delet</th>
				<th>Change status</th>
				<th>Banned</th>
			</tr>';

		foreach($data as $elem){
			if($elem['banned'] == 1) {
				$statusBan = 'to unban';
			} else {
				$statusBan = 'to ban';
			}

		$status = getStatusUser($elem['status_id']);

			$userList .= "<tr>
				<td>{$elem['login']}</td>
				<td>$status</td>
				<td><a href=\"../admin.php?deleteUser={$elem['id']}\">Delete</a></td>
				<td><a href=\"../admin.php?changeStatus={$elem['id']}\">Chenge status  {$elem['login']}</a></td>
				<td><a href=\"../admin.php?ban_id={$elem['id']}\">$statusBan</a></td>
			</tr>";
		}
		$userList .= '</table>';

		echo $userList;
}

											// Delete user account (Admin)
											// Удаление аакаунта позьзователя (Админ)

function userDeleteAdmin($link){

	if(isset($_GET['deleteUser'])){

			$id = $_GET['deleteUser'];
			$query = "DELETE FROM users WHERE id='$id'";
			$result = mysqli_query($link, $query) or die(mysqli_error($link));
			redir('/admin.php');
		}

	}

											// Update user status (Admin)
											// Замена статуса пользователя (Админ)

function userChangeStatus($link, $data){


	if(isset($_GET['changeStatus'])){

			$id = $_GET['changeStatus'];


		foreach($data as $elem){
				if ($elem['id'] == $id and $elem['status_id'] == 1){
						$changedStatus = 2;
						// $statusForm = 'replace witch user';

					} elseif ($elem['id'] == $id and $elem['status_id'] == 2){
						$changedStatus = 1;

						// $statusForm = 'replace witch admin';
					}

			}
				$query = "UPDATE users SET status_id='$changedStatus' WHERE id='$id'";
				$result = mysqli_query($link, $query) or die(mysqli_error($link));

			redir('/admin.php');

		}

}

											// Update user status (Admin)
											// Замена статуса пользователя (Админ)

function userBanned($link, $data){


	if(isset($_GET['ban_id'])){

			$id = $_GET['ban_id'];

		foreach($data as $elem){
				if ($elem['id'] == $id and $elem['banned'] == '0'){
						$ban_id = 1;

					} elseif ($elem['id'] == $id and $elem['banned'] == 1){
						$ban_id = 0;

					}

				}
				$query = "UPDATE users SET banned='$ban_id' WHERE id='$id'";
				$result = mysqli_query($link, $query) or die(mysqli_error($link));

				redir('/admin.php');
	}
}