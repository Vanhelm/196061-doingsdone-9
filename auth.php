<?php
require_once('system/init.php');
if(!empty($user))
{
	header("Location: /");
	exit();
}

$keys = ['email', 'password'];
$errors= [];
$data= [];

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

	if(empty($errors['email']))
	{
		if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
		{
			$errors['email'] = "E-mail введён некорректно";
		}
		else
		{
			$sql = "SELECT * FROM users WHERE email='".$data['email']."'";
			$res = mysqli_query($link, $sql);
			$verify = mysqli_fetch_assoc($res);
		}
	}
	
	if(empty($errors['email']) AND empty($verify))
	{	
		$errors['email'] = "E-mail в базе не найден";
	}
	//Проверяем на ошибки, если email такого нет, то хэши мы сравнивать не будем 
	//соотвтестно и обращаться к несуществующей переменной тоже не будем
	if(empty($errors['email']))
	{
		//А если вот пароль неверен мы выдадим что к такому email пароль неверен
		if(empty($errors['password']) AND !password_verify($data['password'], $verify['password']))
		{
			$errors['password'] = "Пароль не подходит к данному email";
		}
	}

	if(empty($errors))
	{
		$_SESSION['user_id'] = intval($verify['id_user']);
		header("Location: /");
		exit();
	}
}
$content = include_template('auth.php',['data' => $data, 'errors' => $errors]);
$layout_content = include_template('layout-auth.php',['content' => $content, 'title' => "Вход"]);
print ($layout_content);
