<?php
ini_set('error_reporting', E_ALL); 
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);
require_once('functions/function.php');

date_default_timezone_set('Europe/Moscow');
$title = "Дела в порядке";

$link = mysqli_connect("localhost", "root", "", "affairs_order");
mysqli_set_charset($link, "utf8");

if(!$link)
{
	print ("Ошибка подключения: " . mysqli_connect_error());
}

else
{
	$get_projects_user = "SELECT name FROM projects WHERE id_user = 1";
	$res = mysqli_query($link, $get_projects_user);
	$projects = mysqli_fetch_all($res, MYSQLI_ASSOC);

	$get_tasks_user = "SELECT name, status, term FROM tasks";
	$res_task = mysqli_query($link, $get_tasks_user);
	$tasks = mysqli_fetch_all($res_task, MYSQLI_ASSOC);
}