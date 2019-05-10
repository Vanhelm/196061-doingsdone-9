<?php 
require_once 'system/init.php';

if(empty($user))
{
	http_response_code(403);
	header("Location: /");
	exit();
}

$keys = ['name', 'project'];
$currentDate = strtotime('today');
$errors = [];
$data = [];
$id = 0;

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
	foreach ($keys as $val) 
	{
		
		if(isset($_POST[$val]) AND !empty(trim($_POST[$val])))	
		{
			$data[$val] = mysqli_real_escape_string($link, trim($_POST[$val]));
		}
		else
		{
			$errors[$val] = "Это поле обязательно для заполнения";
		}
	}

	if(empty($errors['name']) AND strlen($data['name']) > 60)
	{
		$errors['name'] = "Cлишком длинное имя задачи, максимум 60 символов";
		$data['name'] = ""; 
	}
	if(empty($errors['project']))
	{
		$id = intval($_POST['project']);
		$sql = "SELECT project_id, name FROM projects WHERE project_id= " . $id. " AND id_user= " . $user_id;
		$res = mysqli_query($link, $sql);
		$id_project = mysqli_fetch_all($res, MYSQLI_ASSOC);
		if(empty($id_project))
		{
			$errors['project'] = "так нельзя делать"; 
		}
	}
	if(!empty($_POST['date']))
	{
		if(!is_date_valid($_POST['date']))
		{
				$errors['date'] = "Неверный формат даты";
		}
		elseif(strtotime($_POST['date']) <= $currentDate)
		{
			$errors['date'] = "Дата не может быть меньше текущей";
		}
		else
		{
			$data['date'] = "'".$_POST['date']."'";
		}
		
	}
	else
	{
		$data['date'] = "null";
	}
	
	if(isset($_FILES['file']) AND is_uploaded_file($_FILES['file']['tmp_name']))
	{	
		if(!empty($errors))
		{
			$errors['file'] = "Заполните форму без ошибок и загрузите файл повторно!";
		}
		else
		{
			$file_name = $_FILES['file']['name'];
			$uniq_name = uniqid($file_name);
			$file_path = $_SERVER['DOCUMENT_ROOT'] . "/uploads/";
			$data['file_url'] = "/uploads/" . $uniq_name;
			move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $uniq_name);
		}
	}
	else
	{
		$data['file_url'] = "";
	}
	if(empty($errors))
	{
		$sql = "INSERT INTO tasks (name, project_id, file, id_user, term) VALUES ('" . $data['name']."','".$data['project']."','".$data['file_url']."', '$user_id',".$data['date'].")";
		$result = mysqli_query($link, $sql);
		if($result)
		{
			header("Location: /");
			exit();
		} 
		else
		{
			print_r(mysqli_error($link));
		}
	}
}
$content = include_template('form-task.php', ['projects' => $projects, 'title' => "Добавить задачу", 'errors' => $errors, 'data' => $data, 'id' => $id]);
$layout_content = include_template('layout.php',[
    'projects' => $projects,
    'content' => $content,
  	'title' => $title,
 	'active' => $active_project_id,
  	'name_user' => $user['name']
]);
print ($layout_content);
