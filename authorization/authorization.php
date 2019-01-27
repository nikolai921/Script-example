<?php
include 'function.php';

if(!empty($_SESSION['login'])){
	$title = $_SESSION['login'];
} else {
	$title = 'aut';
}

// headerUser ('/admin.php', 'Admin');

authorizationUnit ($link, $title);

newLink ('/register.php', 'Register');
echo '</br>';
newLink ('/number1.php', 'number1');
echo '</br>';
newLink ('/main.php', 'Main');
echo '</br>';


// var_dump($_SESSION);

// $status = getStatusUser($link);
