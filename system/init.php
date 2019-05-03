<?php
ini_set('error_reporting', E_ALL); 
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);
require_once('functions/functions.php');
require_once('helpers.php');

date_default_timezone_set('Europe/Moscow');
$title = "Дела в порядке";
$active_project_id = 0;
$user_id = 2;

$link = mysqli_connect("localhost", "root", "", "affairs_order");

if(!$link)
{
	print ("Ошибка подключения: " . mysqli_connect_error());
	exit();
}
mysqli_set_charset($link, "utf8");

$sql_projects_user = "SELECT p.project_id AS project_id, p.name AS project_name, COUNT(t.project_id) AS count_item  FROM projects p LEFT JOIN tasks t ON p.project_id = t.project_id WHERE p.id_user = ".$user_id." GROUP BY p.project_id";
$res = mysqli_query($link, $sql_projects_user);
$projects = mysqli_fetch_all($res, MYSQLI_ASSOC);


