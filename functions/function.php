<?php

/*$projects = ["Входящие", "Учёба", "Работа", "Домашние дела", "Авто"];*/
/*$tasks = [
    [
            'name' => 'Собеседование в IT компании',
            'data' => '23.04.2019',
            'category' => 'Работа',
            'complete' => 'нет'
    ],
    [
            'name' => 'Выполнить тестовое задание',
            'data' => '25.12.2018',
            'category' => 'Работа',
            'complete' => 'нет',
    ],
    [
            'name' => 'Сделать задание первого раздела',
            'data' => '21.12.2018',
            'category' => 'Учёба',
            'complete' => 'да',
    ],
    [
            'name' => 'Встреча с другом',
            'data' => '22.12.2018',
            'category' => 'Входящие',
            'complete' => 'нет',
    ],
    [
            'name' => 'Купить корм для кота',
            'data' => 'нет',
            'category' => 'Домашние дела',
            'complete' => 'нет',
    ],
    [
            'name' => 'Заказать пиццу',
            'data' => 'нет',
            'category' => 'Домашние дела',
            'complete' => 'нет',
    ]

        ];*/

/*function calculationTask (array $taskList, $projectName) 
{
    $countItem = 0;

    foreach ($taskList as $key => $val)
    {
        if($val['name'] == $projectName)
        {                   
           $countItem++;
        }
    }
    return $countItem;
}*/



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



