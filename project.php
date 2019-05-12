<?php 
require_once 'system/init.php';

if(empty($user))
{
	header("Location: /");
	exit();
}

$errors = "";
$data = "";

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
	if(isset($_POST['name']) AND !empty(trim($_POST['name'])))
	{
		$data = mysqli_real_escape_string($link, trim($_POST['name']));
	}
	else
	{
		$errors = "Это поле обязательно для заполнения";
	}

	if(empty($errors) AND strlen($data) > 60)
	{
		$errors = "Cлишком длинное имя проекта, максимум 60 символов";
	}

	if(empty($errors))
	{
		$sql = "INSERT INTO projects (name, id_user) VAlUES ('$data', '$user_id')";
		$res = mysqli_query($link, $sql);

		if($res)
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


$content = include_template('project.php', ['errors' => $errors, 'data' => $data]);
$layout_content = include_template('layout.php',[
    'projects' => $projects,
    'content' => $content,
  	'title' => $title,
 	'active' => $active_project_id,
  	'name_user' => $user['name']
]);
print ($layout_content);