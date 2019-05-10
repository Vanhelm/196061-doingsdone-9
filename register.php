<?php
require_once('system/init.php');

if(!empty($user))
{
	header("Location: /");
	exit();
}

$keys = ['email', 'password', 'name'];
$errors = [];
$data=[];

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

	if(!isset($errors['email']) AND !filter_var($data['email'],FILTER_VALIDATE_EMAIL))
	{
		$errors['email'] = "Электронная почта введена неверна";
	}

	if(!isset($errors['email']))
	{
		$sql_email = "SELECT email FROM users WHERE email ='".$data['email']."'";
		$res = mysqli_query($link, $sql_email);
		$email = mysqli_fetch_all($res, MYSQLI_ASSOC);
		if($email)
		{
			$errors['email'] = "Пользователь с таким e-mail уже зарегистрирован";
		}
	}

	if(empty($errors['name']) AND strlen($data['name']) > 60)
	{	
		$errors['name'] = "Cлишком длинное имя";
	}

	if(empty($errors))
	{
		$password = password_hash($data['password'], PASSWORD_DEFAULT);
		$sql = "INSERT INTO users (email, password, name) VALUES ('" . $data['email']. "', '$password' ,". "'" .$data['name'] . "')";
		$result = mysqli_query($link, $sql);
		
		if($result)
		{
			$_SESSION['user_id'] = intval(mysqli_insert_id($link));
			header("Location: /");
			exit();
		}
		else
		{
			print_r(mysqli_error($link));
		}
	}
}



$content = include_template('reg.php', ['data' => $data, 'errors' => $errors]);
$layout_content = include_template('layout-auth.php',['content' => $content, 'title' => "Регистрация"]);
print ($layout_content);
