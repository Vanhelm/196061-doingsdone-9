<?php 
require_once 'system/init.php';
$name = "";
$currentDate = time();
$error = [];
$id = 3;
$select_project_id = 0;


if(!empty($_POST))
{
	if(!empty($_POST['name']) AND !empty(trim($_POST['name'])))
	{
		$name = mb_strimwidth($_POST['name'], 0, 60, "...");
		echo $name;
	}

	else
	{	
		$error[] = "name";
	}

	if(strtotime($_POST['date']))
	{
		echo "Все норм";

		if(strtotime($_POST['date']) >= $currentDate)
		{
			echo "Огонь";
		}	
		else 
		{
			$error[] = "error current";
		}

	}
	else
	{
		$error[] = "date";
	}
	
	if(!empty($_POST['project']))
	{
		$transfer_id = intval($_POST['project']);
		$sql_project_id = "SELECT project_id FROM projects WHERE project_id= " . $transfer_id. " AND id_user= " . $user_id;
		$res = mysqli_query($link, $sql_project_id);
		$id_project = mysqli_fetch_all($res, MYSQLI_ASSOC);

		if(!empty($id_project))
		{
			echo "все кул";
		}
		else
		{
			echo "ну и зачем ты это делаешь?";
		}
	}
}

print(visual_error($error));

$layout_content = include_template('form-task.php', ['projects' => $projects, 'title' => "Добавить задачу", 'error' => $error]);
print ($layout_content);
