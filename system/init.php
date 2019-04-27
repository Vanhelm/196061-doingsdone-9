<?php
ini_set('error_reporting', E_ALL); 
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);
require_once('functions/function.php');
require_once('helpers.php');

date_default_timezone_set('Europe/Moscow');
$title = "Дела в порядке";

$link = mysqli_connect("localhost", "root", "", "affairs_order");
mysqli_set_charset($link, "utf8");

if(!$link)
{
	print ("Ошибка подключения: " . mysqli_connect_error());
	exit();
}

else
{
	$get_projects_user = "SELECT p.project_id, p.name AS project_name, COUNT(t.project_id) AS count_item  FROM projects p LEFT JOIN tasks t ON p.project_id = t.project_id WHERE t.id_user = 1 AND t.status=0 GROUP BY p.project_id";
	$res = mysqli_query($link, $get_projects_user);
	$projects = mysqli_fetch_all($res, MYSQLI_ASSOC);

	$get_tasks_user = "SELECT name, status, term FROM tasks WHERE id_user = 1";
	$res_task = mysqli_query($link, $get_tasks_user);
	$tasks = mysqli_fetch_all($res_task, MYSQLI_ASSOC);
}