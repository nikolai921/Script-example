<?php
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

	usersTable($link);