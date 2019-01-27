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


if(!empty($_GET['loginProfile'])){
	$idUser = $_GET['loginProfile'];
}

$query = "SELECT * FROM users WHERE id='$idUser'";
$result = mysqli_query($link, $query) or die(mysqli_error($link));
for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

function calculate_age($birthday){

	$birthday_timestamp = strtotime($birthday);
	$age = date('Y') - date('Y', $birthday_timestamp);
	if(date('md', $birthday_timestamp) > date('md')){
		$age--;
	}

	return $age;

}

calculate_age($data[0]['birthday']);


function userProfile($data){

// необходимо определение параметра $data через запрос к БД,
// в формате массив -> подмасив с необходимыми парамтерами
// it is necessary to define the $ data parameter through a query to the database
// in the format of the array -> with the necessary paramters

$age = calculate_age($data[0]['birthday']);

$userProf = '<table>
			<tr>
				<th>Login</th>
				<th>Birthday</th>
				<th>Check in</th>
				<th>Country</th>
			</tr>';

		foreach($data as $elem){
			$userProf .= "<tr>
				<td>{$elem['login']}</td>
				<td>$age</td>
				<td>{$elem['dateNow']}</td>
				<td>{$elem['country']}</td>
			</tr>";
		}
		$userProf .= '</table>';

		echo $userProf;
}

userProfile($data);

