<?php
$title = 'admin main page';

echo '<p><a href="../index.php">Home</a></p>';
echo '<p><a href="logout.php">logout</a></p>';
echo '<a href="../index.php?pages=insert">Insert</a>';

//  функция отображающая таблицу всех статей из БД в разделе администрирования
	function showPageTable($link, $info = ''){

		$query = "SELECT id, dateSend, name, url, article FROM Blog";
		$result = mysqli_query($link, $query) or die(mysqli_error($link));
		for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

		$content = '<table>
			<tr>
				<th>name</th>
				<th>dateSend</th>
				<th>article</th>
				<th>edit</th>
				<th>delete</th>
			</tr>';

		foreach($data as $elem){
			$content .= "<tr>
				<td>{$elem['name']}</td>
				<td>{$elem['dateSend']}</td>
				<td>{$elem['article']}</td>
				<td><a href=\"../index.php?edit={$elem['id']}&pages=update\">edit</a></td>
				<td><a href=\"?delete={$elem['id']}&pages=admin\">delete</a></td>
			</tr>";
		}
		$content .= '</table>';

		echo $content;
}

//  функция удаления указоной статьи из всего списка
function deletePage($link){

	if(isset($_GET['delete'])){
		$id = $_GET['delete'];
		$query = "DELETE FROM Blog WHERE id='$id'";
		mysqli_query($link, $query) or die(mysqli_error($link));
		return true;
	} else {
		return false;
	}
}


if(deletePage($link)){
		$info = 'Page deleted successfully';
	} else {
		$info = '';
	}

echo '<p>'.$info.'</p>';

showPageTable($link, $info);









