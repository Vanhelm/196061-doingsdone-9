<?php
require_once('system/init.php');
if(isset($_SESSION['user_id']))
{
	$show_complete_tasks = rand(0, 1);	
	$content_page;
	$tasks = [];

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
    	'active' => $active_project_id
	]);
}
else
{
	$layout_content = include_template('guest.php', ['title' => "Дела в порядке"]);
}



print ($layout_content);
?>