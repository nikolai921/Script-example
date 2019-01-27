<?php

echo '<p><a href="admin/indexAdmin.php?pages=admin">Admin</a></p>';

// текст сообщения при удачном удаление
if(!empty($_GET['added'])){
	$added = [
		'text' => 'Page add seccessfully',
		'status' => 'success'
	];
}

if(isset($_GET['page'])){
	$page = $_GET['page'];

// при наличие GET запроса проводится проверка на наличие в БД соотвествуещего ID из GET параметра
	$query = "SELECT * FROM Blog WHERE id=$page";
	$result = mysqli_query($link, $query) or die(mysqli_error($link));
	// формируется массив с результатом
	for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

		// если массив пустой следовательно выводится ошибка из файла 404,
		if(empty($data)){

				include '404.php';

			} else {
				// если массив заполнен производится его перебокра и формируется непосредственно страница статьй с основными ее параметрами
				foreach($data as $elem){

					echo '<body>
						     <a href="index.php">Home</a>
								<div class="note">
									<p>
										<span class="date">'.$elem['dateSend'].'</span>
									</p>
									<p>
										<span class="name">'.$elem['name'].'</span>
									</p>
									<p>
										<span class="article">'.$elem['article'].'</span>
									</p>
								</div>
						</body>';

						$title = $elem['name'];
					}
				}


} else {
// если GET параметр не передан то формируется полный список, всех статей.
	$query = "SELECT * FROM Blog ORDER BY dateSend DESC";
	$result = mysqli_query($link, $query) or die(mysqli_error($link));
	// формируется массив с результатом
	for ($date = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

	foreach($data as $elem){

	echo '<body>
			<div class="note">
				<p>
					<span class="date">'.$elem['dateSend'].'</span>
					<span> <a href="index.php?page='.$elem['id'].'">'.$elem['name'].'</a> </span>
				</p>
			</div>
		</body>';

		$title = 'Блог Фотографа';
	}

}




