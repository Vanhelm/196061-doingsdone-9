<?php
require_once('system/init.php');
if(!empty($user))
{	

	$tasks = [];
	$show_complete_tasks = 0;
	$date_select = "";
	$search = "";	


	if(isset($_COOKIE["show"]))
	{
		$show_complete_tasks = $_COOKIE["show"];
	}

	if(isset($_GET['show_completed']))
	{
		$show_complete_tasks = intval($_GET['show_completed']);
		setcookie("show", $show_complete_tasks, time() + 86400, '/');
	}

	if(isset($_GET['task_id']))
	{
		$task_id = intval($_GET['task_id']);
		$sql = "SELECT * FROM tasks WHERE id_task = '$task_id' AND id_user = '$user_id'";
		$res = mysqli_query($link, $sql);
		$task_complete = mysqli_fetch_assoc($res);

		if($task_complete['status'] == 0)
		{
			$status = 1;
		}
		else
		{
			$status = 0;
		}

		$sql_update = "UPDATE tasks SET status = '$status' WHERE id_task = '$task_id' AND id_user = '$user_id'";
		$result = mysqli_query($link, $sql_update);
	}

	if(isset($_GET['id']))
	{
		$active_project_id = intval($_GET['id']);
		$sql_project_id = "SELECT project_id FROM projects WHERE project_id= " . $active_project_id. " AND id_user= " . $user_id;
		$res = mysqli_query($link, $sql_project_id);
		$id_project = mysqli_fetch_all($res, MYSQLI_ASSOC);
	}

	if(empty($id_project) AND isset($_GET['id']))
	{
		error_404($projects, $title, $user);
	}
 	else
	{
		$tasks = get_task($link, $active_project_id, $user_id, $show_complete_tasks);
		$content = include_template('index.php', ['tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks, 'active' => $active_project_id]);
	}

	if(isset($_GET['date']))
	{
		$date_select = mysqli_real_escape_string($link, $_GET['date']);
		$tasks = filter_tasks($date_select, $user_id, $link, $active_project_id);

		if($date_select AND $tasks == "error")
		{
			error_404($projects, $title, $user);				
		}
		else
		{	
			$content = include_template('index.php', [
				'tasks' => $tasks, 
				'show_complete_tasks' => $show_complete_tasks, 
				'date_select' => $date_select,
				'active' => $active_project_id
			]);
		}

	}
	if(isset($_GET['search']))
	{
		$search = mysqli_real_escape_string($link, $_GET['search']);
		$sql_find = "SELECT * FROM tasks WHERE MATCH(name) AGAINST('$search') AND id_user='$user_id' ORDER BY dt_add DESC";
		$res = mysqli_query($link, $sql_find);
		$tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);

		if(empty($tasks))
		{
			$content = include_template('not-find.php');
		}
		else
		{
			$content = include_template('index.php', [
				'tasks' => $tasks, 
				'show_complete_tasks' => $show_complete_tasks, 
				'active' => $active_project_id
			]);			
		}
	}

	if(isset($_GET['date']))
	{
		$date_select = $_GET['date'];
		if(filter_tasks($date_select, $user_id, $link) == "error")
		{
			http_response_code(404);
			$content = include_template('error404.php');				
		}
		else
		{	
			$tasks = filter_tasks($date_select, $user_id, $link);
			$content = include_template('index.php', [
				'tasks' => $tasks, 
				'show_complete_tasks' => $show_complete_tasks, 
				'date_select' => $date_select
			]);
		}

	}
	$layout_content = include_template('layout.php',[
    	'projects' => $projects,
    	'tasks' => $tasks,
    	'content' => $content,
    	'title' => $title,
    	'active' => $active_project_id,
    	'name_user' => $user['name'],
    	'date_select' => $date_select
	]);
}
else
{
	$layout_content = include_template('guest.php', ['title' => $title]);
}

print ($layout_content);
?>