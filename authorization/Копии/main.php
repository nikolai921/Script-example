<?php
include 'function.php';

newLink ('/number1.php', 'number1');
echo '</br>';
newLink ('/register.php', 'Register');
echo '</br>';

if($_SESSION['auth'] == true){
	echo 'User is authorized';


}

