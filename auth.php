<?php
require_once('system/init.php');
if(!empty($users))
{
	header("Location: /");
}
else
{
	$keys = ['email', 'password'];
	$errors=[];
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

		$sql = "SELECT * FROM users WHERE email='".$data['email']."'";
		$res = mysqli_query($link, $sql);
		$verify = mysqli_fetch_assoc($res);

		if(empty($errors['email']) AND !filter_var($data['email'], FILTER_VALIDATE_EMAIL))
		{
			$errors['email'] = "E-mail введён некорректно";
		}

		if(empty($errors['email']) AND empty($verify['email']))
		{	
			$errors['email'] = "E-mail в базе не найден";
		}
		if(empty($errors['password']) AND !password_verify($data['password'], $verify['password']))
		{
			$errors['password'] = "Неверный пароль";
		}
		if(empty($errors))
		{
			$_SESSION['user_id'] = intval($verify['id_user']);
			header("Location: /");
		}
	}
	$content = include_template('auth.php',['data' => $data, 'errors' => $errors]);
	$layout_content = include_template('layout-auth.php',['content' => $content, 'title' => "Вход"]);
	print ($layout_content);
}