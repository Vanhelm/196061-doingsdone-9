<?php
require_once('system/init.php');

$show_complete_tasks = rand(0, 1);
$content_page = include_template('index.php', ['tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks]);
$layout_content = include_template('layout.php',[
    'projects' => $projects,
    'tasks' => $tasks,
    'content' => $content_page,
    'title' => $title
]);

print ($layout_content);
?>
