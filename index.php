<?php
require_once('system/init.php');
if(!empty($user))
{
	$show_complete_tasks = 1;	
	$content_page;
	$tasks = [];

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
	}

	$sql_project_id = "SELECT project_id FROM projects WHERE project_id= " . $active_project_id. " AND id_user= " . $user_id;
	$res = mysqli_query($link, $sql_project_id);
	$id_project = mysqli_fetch_all($res, MYSQLI_ASSOC);

	if(empty($id_project) AND isset($_GET['id']))
	{
		http_response_code(404);
		$content = include_template('error404.php');
	}
 	else
	{
		$tasks = get_task($link, $active_project_id, $user_id, $show_complete_tasks);
		$content = include_template('index.php', ['tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks]);
	}

	$layout_content = include_template('layout.php',[
    	'projects' => $projects,
    	'tasks' => $tasks,
    	'content' => $content,
    	'title' => $title,
    	'active' => $active_project_id,
    	'name_user' => $user['name']
	]);
}
else
{
	$layout_content = include_template('guest.php', ['title' => $title]);
}



print ($layout_content);
?>