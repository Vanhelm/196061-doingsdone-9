<?php
require_once('system/init.php');

$keys = ['email', 'password'];
$data=[];
$errors=[];

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

	if(empty($errors['email']) AND !filter_var($data['email'], FILTER_VALIDATE_EMAIL))
	{
		$errors['email'] = "E-mail введён некорректно";
	}

	if(empty($errors['email']))
	{
		$sql_email = "SELECT * FROM users WHERE email='".$data['email']."'";
		$res = mysqli_query($link, $sql_email);
		$verify = mysqli_fetch_assoc($res);
		
		if(empty($verify))
		{
			$errors['email'] = "E-mail в базе не найден";
		}
	}
	if(empty($errors['password']))
	{

		if(!password_verify($data['password'], $verify['password']))
		{
			$errors['password'] = "Неверный пароль";
		}
	}
	if(empty($errors))
	{
		header("Location: /");
		session_start();
		$_SESSION['user_id'] = $verify['id_user'];
		$_SESSION['name'] = $verify['name'];
	}
}


$content = include_template('auth.php',['data' => $data, 'errors' => $errors]);
$layout_content = include_template('layout-auth.php',['content' => $content, 'title' => "Вход"]);
print ($layout_content);