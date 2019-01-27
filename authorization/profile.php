<?php
include 'function.php';

headerUser ('/admin.php', 'Admin');

if(isset($_SESSION['auth'])){

newLink ('/main.php', 'Main');
echo '</br>';
newLink ('/users.php', 'Users');

		if(!empty($_GET['loginProfile'])){
			$idUser = $_GET['loginProfile'];

		$query = "SELECT * FROM users WHERE id='$idUser'";
		$result = mysqli_query($link, $query) or die(mysqli_error($link));
		for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
		userProfile($data);

		}

	} else {
	newLink ('/authorization.php', 'Authorized');
	echo '</br>';
	echo 'Pleas login';
}