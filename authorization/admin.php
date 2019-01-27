<?php
include 'function.php';

if(isset($_SESSION['status']) and  $_SESSION['status'] == 'admin'){
	$query = "SELECT * FROM users";
	$result = mysqli_query($link, $query) or die(mysqli_error($link));
	for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);


	userChangeStatus($link, $data);
	userDeleteAdmin($link);
	$statusBan = userBanned($link, $data);

	headerUser ('/admin.php', 'Admin');
	newLink ('/main.php', 'Main');
	echo '</br>';

	userListAdmin($data, $statusBan);



var_dump($_GET);
var_dump($_POST);
var_dump($statusBan);

}


