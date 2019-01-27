<?php
include 'function.php';

headerUser ('/admin.php', 'Admin');

if(isset($_SESSION['status']) and  $_SESSION['status'] == 'admin'){
newLink ('/admin.php', 'Admin Page');
echo '</br>';
}

newLink ('/authorization.php', 'Login Page');
echo '</br>';
newLink ('/register.php', 'Register');
echo '</br>';
newLink ('/users.php', 'User table');
echo '</br>';

if(isset($_SESSION['auth'])){
newLink ('/number1.php', 'number1');
echo '</br>';
newLink ('/personalyArea.php', 'Personaly Area');
echo '</br>';
	echo 'User is authorized';
} else {
	echo 'Please login';
}

var_dump($_SESSION);