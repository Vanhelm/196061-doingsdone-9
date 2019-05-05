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
function get_task($link, $project_id, $user_id, $show_complete_task)
{
	$sql_tasks_user = "SELECT name, status, term FROM tasks WHERE id_user= " . $user_id;
	
	if($show_complete_task == 0)
	{
		$sql_tasks_user .= " AND status=0";
	}

	if($project_id>0)
	{
		$sql_tasks_user .= " AND project_id = " .$project_id;
	}

	$res_task = mysqli_query($link, $sql_tasks_user);
	$tasks = mysqli_fetch_all($res_task, MYSQLI_ASSOC);
	return $tasks;
}


function visual_error(array $error)
{
	if(!empty($error))
	{
		foreach ($error as $key => $value) 
		{
			if($value == "name")
			{
				print "Это поле должно быть заполненным";
			}
			if($value == "date")
			{
				print "Дата должна быть больше, либо равна текущей";
			}
			if($value == "date current")
			{
				echo "Дата не введена или неверный формат(ГГГГ.ММ.ДД.)";
			}
			if($value == "ошибка в id")
			{
				echo "Вот это вообще некрасиво";
			}
		}
	}
	else
	{
		echo "Красавчик";
	}
}