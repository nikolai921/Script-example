<?php

echo '<a href="admin/indexAdmin.php?pages=admin">Main Page</a>';

$title = 'admin edit page';

if(!empty($_GET['edit'])){
	$id = $_GET['edit'];
}

$query = "SELECT * FROM Blog WHERE id='$id'";
$result = mysqli_query($link, $query) or die(mysqli_error($link));
$data = mysqli_fetch_assoc($result);

	if(!empty($data)){
		$name = $data['name'];
		$article = $data['article'];

	} else {
		echo 'Page not found';
	}

	if((!empty($_POST['name'])) or (!empty($_POST['article']))){
		// экранируем спец символы в строке - что бы можно было добавлять слова типа Д'Артаньян
		$name = mysqli_real_escape_string($link, $_POST['name']);
		$date = date('Y.m.d H.i.s', time());
		// экранируем спец символы в строке - что бы можно было добавлять слова типа Д'Артаньян
		$article = mysqli_real_escape_string($link, $_POST['article']);

		// проверям наличие в БД
		$query = "SELECT * FROM Blog WHERE url='$name' && id!='$id'";
		$result = mysqli_query($link, $query) or die(mysqli_error($link));
		$row = mysqli_fetch_assoc($result);

		// при наличие проводим изменение текущих параметров
		if(empty($row)){
			$query = "UPDATE blog SET dateSend='$date', name='$name',
	 		article='$article', url='$name' WHERE id='$id'";
			$result = mysqli_query($link, $query) or die(mysqli_error($link));

		} else {
			echo $see = 'Page this url with id' . ' ' . $row['id'] . ' ' . 'exssists.';
		}
	}

gettingInputForm($name, $article);

