<?php
require_once('system/init.php');

$keys = ['email', 'password', 'name'];
$data = [];
$errors = [];

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
		if(filter_var($data['email'],FILTER_VALIDATE_EMAIL) !== FALSE)
		{
			$sql_email = "SELECT email FROM users WHERE email ='".$data['email']."'";
			$res = mysqli_query($link, $sql_email);
			$email = mysqli_fetch_all($res, MYSQLI_ASSOC);

			if(!empty($email))
			{
				$errors['email'] = "Этот email уже зарегистрирован";
			} 
		}
		else
		{
			$errors['email'] = "Электронная почта введена неверна";
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
			header("Location: /");
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