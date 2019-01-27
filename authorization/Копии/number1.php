<?php
include 'function.php';

if(isset($_SESSION['auth'])){
	echo 'access is allowed' . '</br>';
	newLink ('/logout.php', 'Exit');
} else {
	newLink ('/authorization.php', 'Login Page');
}


