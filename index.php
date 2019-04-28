<?php
require_once('system/init.php');

$show_complete_tasks = rand(0, 1);


/*$get_tasks_user = "SELECT name, status, term FROM tasks WHERE id_user = 1";
$res_task = mysqli_query($link, $get_tasks_user);
$tasks = mysqli_fetch_all($res_task, MYSQLI_ASSOC);*/

$content_page = include_template('index.php', ['tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks]);
$layout_content = include_template('layout.php',[
    'projects' => $projects,
    'tasks' => $tasks,
    'content' => $content_page,
    'title' => $title
]);

print ($layout_content);
?>
