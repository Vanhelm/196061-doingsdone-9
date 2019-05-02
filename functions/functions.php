<?php

/**
* Подсчёт даты
* 
* Функция сравнивает текущую дату и дату завершения задачи
* и возвращает true или false
* 
* @param $data - дата завершения в формате TIMESTAMP
* @param $taskComplete int - статус задачи(выполнена или нет)
* @return bool
*/
function calculationDate($data, $taskComplete){
	if($data === NULL or $taskComplete === "1")
	{ 
		return false; 
	} 

	$currentData = time(); 
	$formatDate = strtotime($data); 
	$dateOfComplete = $formatDate - $currentData; 
	
	if($dateOfComplete <= 86400)
	{ 
		return true; 
	}

	return false; 
}

/**
* Корректное отображение даты
* 
* Функция убирает время и оставляет только дату
* 
* 
* @param $data дата которую надо привести в правильный вид
* @return date
*/
function correct_visual_date($data)
{
	$date = $data;
	$dt = new DateTime($date);
	return $dt->format('d.m.Y');
}

/**
* Получить задачи
* 
* Функция позволяет получить массив с задачами
* по заданным условиям
* 
* @param $link подключение к БД
* @param $project_id id проекта
* @param $user_id id пользователя
* @return array
*/
function get_task($link, $project_id, $user_id)
{
	$where_task = "";
	
	if($project_id>0)
	{
		$where_task = "project_id = " .$project_id. " AND";
	}

	$get_tasks_user = "SELECT name, status, term FROM tasks WHERE " .$where_task. " id_user= " . $user_id;
	$res_task = mysqli_query($link, $get_tasks_user);
	$tasks = mysqli_fetch_all($res_task, MYSQLI_ASSOC);
	return $tasks;
}



