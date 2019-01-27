<?php

echo '<a href="admin/indexAdmin.php?pages=admin">Main Page</a>';

$title = 'admin insert page';

// проводится проверка на наличие переданой статьй параметром POST, в БД. что бы избежать повторения статей.
if((!empty($_POST['name'])) or (!empty($_POST['article']))){
	$name = mysqli_real_escape_string($link, $_POST['name']);
	$date = date('Y.m.d H.i.s', time());
	$article = mysqli_real_escape_string($link, $_POST['article']);

	$query = "SELECT COUNT(*) as count  FROM Blog WHERE url='$name'";
	$result = mysqli_query($link, $query) or die(mysqli_error($link));
	$row = mysqli_fetch_assoc($result)['count'];

	if($row){
		$_SESSION['message'] = [
		'text' => 'Page with this url exsist',
		'status' => 'error'
		];
	} else {
		$query = "INSERT INTO blog SET dateSend='$date', name='$name', url='$name',
		article = '$article'";
			if(mysqli_query($link, $query) == true){
				$_SESSION['message'] = [
					'text' => 'Page add seccessfully',
					'status' => 'success'
				];
				// header('Location: indexAdmin?added=true');
				} else {
					die(mysqli_error($link));
				}

			}
}

if(isset($_SESSION['message'])){
	$status = $_SESSION['message']['status'];
	$text = $_SESSION['message']['text'];

	echo "<p class=\"$status\">$text</p>";
}

// данные сформированы для формы, что бы отображать фоном загруженые ранее данные.
if((!empty($_POST['name'])) or (!empty($_POST['article']))){
	$name = ($_POST['name']);
	$date = date('Y.m.d H.i.s', time());
	$article = ($_POST['article']);
} else {
	$name = '';
	$date = '';
	$article = '';
}
// функция принимается из файла password
gettingInputForm($name, $article);


