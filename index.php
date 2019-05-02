<?php
header("HTTP/1.0 404 Not Found");
require_once('system/init.php');

$show_complete_tasks = rand(0, 1);	
$content_page;
if(empty($tasks) or empty($projects))
{
	http_response_code(404);
	//почему-то не подключает
	$content_page = include_template('/system/error404.php');
}
else{
$content_page = include_template('index.php', ['tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks]);
}

$layout_content = include_template('layout.php',[
    'projects' => $projects,
    'tasks' => $tasks,
    'content' => $content_page,
    'title' => $title,
    'active' => $active_project_id
]);

print ($layout_content);
?>
