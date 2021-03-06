<?php
//Открываем сессию
session_start();

//Включаем ошибки
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

//Ставим время МСК
date_default_timezone_set('Europe/Moscow');

require_once('functions/functions.php');
require_once('helpers.php');

//Объявляем переменные
$user = [];
$user_id = 0;
$name_user = "";
$title = "Дела в порядке";
$active_project_id = null;

//Подклчюаемся к БД и задаем кодировку
$link = mysqli_connect("localhost", "root", "", "affairs_order");

if (!$link) {
    print ("Ошибка подключения: " . mysqli_connect_error());
    exit();
}
mysqli_set_charset($link, "utf8");

//Если в сессии передан user_id достаем данные
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql_user = "SELECT * FROM users WHERE id_user = " . $user_id;
    $result = mysqli_query($link, $sql_user);
    $user = mysqli_fetch_assoc($result);

    //достаем проекты
    $sql_projects_user = "SELECT p.project_id AS project_id, p.name AS project_name, COUNT(t.project_id) AS count_item  FROM projects p LEFT JOIN tasks t ON p.project_id = t.project_id WHERE p.id_user = " . $user_id . " GROUP BY p.project_id";
    $res = mysqli_query($link, $sql_projects_user);
    $projects = mysqli_fetch_all($res, MYSQLI_ASSOC);
}
	









