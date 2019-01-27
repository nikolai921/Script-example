<?php
include 'function.php';

headerUser ('/admin.php', 'Admin');

newLink ('/authorization.php', 'Authorized');

$title='Insert';

if(!empty($_POST['password']) and !empty($_POST['login']) and !empty($_POST['confirm'])){

$dataInform = correctDataEntry($link);

	formDataSend($dataInform, $title);

} else {
	formDataSend($dataInform = 0, $title);
}

var_dump($_SESSION);
// var_dump($_POST);
// var_dump($dataInform);




