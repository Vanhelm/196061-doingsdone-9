<?php 
require_once 'system/init.php';

$keys = ['name', 'project'];
$currentDate = time() - 86400;
$errors = [];
$data = ['name' => "", 'date' => null, 'project' => "", 'file_url' => "", 'user_id' => ""];
$data ['user_id'] = $user_id;

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
			$errors[$val] = "Это поле обязательно для заполнение";
		}
	}

	if(empty($errors['name']) AND strlen($data['name']) > 60)
	{
		$errors['name'] = "Cлишком длинное имя задачи";
		$data['name'] = ""; 
	}
	if(empty($errors['project']))
	{
		$id = intval($_POST['project']);
		$sql = "SELECT project_id FROM projects WHERE project_id= " . $id. " AND id_user= " . $user_id;
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
			$data['date'] = ""; 
		}
		elseif(strtotime($_POST['date']) <= $currentDate)
		{
			$errors['date'] = "Дата не может быть меньше текущей";
			$data['date'] = ""; 
		}
		else
		{
			$data['date'] = mysqli_real_escape_string($link, $_POST['date']);
		}
		
	}
	
	if(isset($_FILES['file']) AND is_uploaded_file($_FILES['file']['tmp_name']))
	{	
		if(!empty($errors))
		{
			$errors['file'] = "Файл доступен для загрузки после заполнения всех полей";
		}
		$file_name = $_FILES['file']['name'];
		$uniq_name = uniqid($file_name);
		$file_path = $_SERVER['DOCUMENT_ROOT'] . "/uploads/";
		$data['file_url'] = "/uploads/" . $uniq_name;
		move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $uniq_name);
	}
	if(empty($errors))
	{
		$sql = "INSERT INTO tasks (name, project_id, term, file, id_user) VALUES ('" . $data['name']."','".$data['project']."','".$data['date']."','".$data['file_url']."','".$data['user_id']."')";
		$result = mysqli_query($link, $sql);
		if($result)
		{
			header("Location: /");
		} 
	}
}
print_r($data);
$content = include_template('form-task.php', ['projects' => $projects, 'title' => "Добавить задачу", 'errors' => $errors, 'data' => $data]);
$layout_content = include_template('layout.php',[
    'projects' => $projects,
    'content' => $content,
    'title' => $title,
    'active' => $active_project_id
]);
print ($layout_content);
