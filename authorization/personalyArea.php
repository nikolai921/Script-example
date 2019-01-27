<?php
include 'function.php';


// var_dump($_SESSION);

headerUser ('/admin.php', 'Admin');

echo '</br>';
newLink ('/authorization.php', 'Authorized');
echo '</br>';
newLink ('/main.php', 'Main');


$title='Update';

if(isset($_SESSION['auth'])){

	$id = $_SESSION['id'];
	$query = "SELECT * FROM users WHERE id='$id'";
	$result = mysqli_query($link, $query) or die(mysqli_error($link));
	$row = mysqli_fetch_assoc($result);


   	if(isset($_POST['delet_password'])){
		$dataInform[] = userPageDelet($link,$row);
		$dataInform['Delet_page'] = 'Page deleted';
		} else {
			$dataInform['Delet_page'] = 'Page not deleted';
		}

	if(isset($_POST['login'])){
		$dataInform = array_merge($dataInform, correctChangeData($link,$row));
		// necessary due to the fact that this option does not send data on these parameters
		// необходимо по причине того что в данном варианте не отправляются данные по этим параметрам
		$dataInform['PasswordEnteredInform'] = '';
		$dataInform['OldPasswordCorrect'] = '';
		$dataInform['confirm'] = '';
		formChangeData($dataInform, $title, $row);
		formPageDelet($dataInform, $title);

		} elseif(isset($_POST['old_password']) and isset($_POST['password']) and isset($_POST['confirm'])){
			$dataInform[] =  array_merge($dataInform, correctPasswordData($link,$row));
			// necessary due to the fact that this option does not send data on these parameters
			// необходимо по причине того что в данном варианте не отправляются данные по этим параметрам
			$dataInform['LoginEnteredInform'] = '';
			$dataInform['e_mailEnteredInform'] = '';
			$dataInform['BirthdayEnteredInform'] = '';
			$dataInform['LoginExists'] = '';
			formChangeData($dataInform, $title, $row);
			formPageDelet($dataInform, $title);

			} else {
				formChangeData($dataInform = 0, $title, $row);
				formPageDelet($dataInform, $title);
			}
// var_dump($dataInform);
var_dump($_POST);
var_dump(correctChangData($link,$row));

}



