<?php

/**
* Подсчёт даты
* 
* Функция сравнивает текущую дату и дату завершения задачи
* и возвращает true или false
* 
* @param string $data - дата завершения в формате TIMESTAMP
* @param int $taskComplete  - статус задачи(выполнена или нет)
* @return bool
*/
function calculationDate($data, $taskComplete){
	if($data == NULL or $taskComplete === "1")
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
* @param string $data дата которую надо привести в правильный вид
* @return date
*/
function correct_visual_date($data)
{
	if($data == NULL)
	{
		return NULL;
	}
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
* @param string $link подключение к БД
* @param int $project_id id проекта
* @param int $user_id id пользователя
* @return array
*/
function get_task($link, $project_id, $user_id, $show_complete_task)
{
	$sql = "SELECT * FROM tasks  WHERE id_user= " . $user_id;
	
	if($show_complete_task == 0)
	{
		$sql .= " AND status=0";
	}

	if($project_id>0)
	{
		$sql .= " AND project_id = " .$project_id;
	}

	$sql .= " ORDER BY dt_add DESC";

	$res_task = mysqli_query($link, $sql);
	$tasks = mysqli_fetch_all($res_task, MYSQLI_ASSOC);
	return $tasks;
}
