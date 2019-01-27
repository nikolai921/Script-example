<?php
// Изначально было несколько страниц, по этому был массив парролей для подбора нужного
$arrayPassword = [
'admin' => '12345',
'insert' => '11111',
'update' => '33333',
];

// массив ссылок для функции INCLUDE для автоматического перехода
$arrayInclude = [
'admin' => '../admin/headerAdmin.php',
'insert' => 'admin/insert.php',
'update' => 'admin/update.php',
];

// массив Заголовков для страниц которые открыты
$arrayHeadLine = [
'admin' => 'Раздел Администратора',
'insert' => 'Добавление страницы',
'update' => 'Редактироваание страницы',
];

// производится перебор массивов по заданному индикатору в GET параметре.
if(isset($_GET['pages'])){
		foreach($arrayHeadLine as $key => $elem){
		if($_GET['pages'] == $key){
			$headline = $elem;
		}
	}

	foreach($arrayPassword as $key => $elem){
		if($_GET['pages'] == $key){
			$password = md5($elem);
		}
	}

	foreach($arrayInclude as $key => $elem){
		if($_GET['pages'] == $key){
			$include = $elem;
		}
	}
}

// определяется был ли авторизирован пользователь ранее, если да то в сессию записывается переменная AUTH = true
if(isset($_POST['password']) && ($password == md5($_POST['password']))){
	$_SESSION['auth'] = true;
	/*$_SESSION['massage'] = [
			'text' => 'Page deleted successfully',
			'status' => 'sucess'
	];*/
}

// проверка введенного адресса на соответсвие 3 основным типам которые используются на стартовой странице, важно присвоение переменной значение 1 при его соответсвие.
$uriIndex = preg_match('#^\/index\.php(?:\?page\=[0-9]+)?$|^\/$#', $_SERVER['REQUEST_URI']);

// цикл проверяет если мы находимся на страницах соответсвующих 3 типам (стартовой), то INCLUDE базовый файл
// если в адресе находится ссылка на другие страницы (это в основном админка) следовательно при наличии в текущей сессии введенного пароля AUTH,
// ICLUDE указанные файлы. которые были выбраны выше стр. 24
// в ином случая появляется форма с запросом на ввод пароля.
if($uriIndex == 1){
	echo '<h1>Блог Фотографа</h1>';
	include 'header.php';
} elseif (isset($_SESSION['auth']) && ($_SESSION['auth'] == true)){
	echo '<h1>'.$headline.'</h1>';
	include $include;
} else {
	echo '<form action = "" method = "POST">
			<input type="password" name="password">
			<input type="submit" value="Пароль">
		</form>';
	}

// стандартная форма для страниц INSERT UPDATE
function gettingInputForm($name, $article){

	echo '<form action="" method="POST">
			<br>name:<br>
			<p><input name="name" class="form-control" placeholder="'.$name.'"></p>
			article:<br>
			<p><textarea name="article" class="form-control" placeholder="'.$article.'"></textarea></p>
			<p><input type="submit" class="btn btn-info btn-block" value="Сохранить"></p>
		</form>';

}
