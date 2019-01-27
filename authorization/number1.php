<?php
include 'function.php';

headerUser ('/admin.php', 'Admin');

if(isset($_SESSION['auth'])){
	echo 'access is allowed' . '</br>';
	newLink ('/logout.php', 'Exit');
} else {
	newLink ('/authorization.php', 'Login Page');
}


