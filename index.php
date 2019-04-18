<?php
ini_set('error_reporting', E_ALL); 
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);
require_once('helpers.php');

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$projects = ["Входящие", "Учёба", "Работа", "Домашние дела", "Авто"];
$tasks = [
        [
                'task' => 'Собеседование в IT компании',
            'data' => '01.12.2018',
            'category' => 'Работа',
            'complete' => 'нет'
        ],
    [
            'task' => 'Выполнить тестовое задание',
            'data' => '25.12.2018',
            'category' => 'Работа',
            'complete' => 'нет',
    ],
    [
        'task' => 'Сделать задание первого раздела',
            'data' => '21.12.2018',
            'category' => 'Учёба',
            'complete' => 'да',
    ],
    [
        'task' => 'Встреча с другом',
            'data' => '22.12.2018',
            'category' => 'Входящие',
            'complete' => 'нет',
    ],
    [
        'task' => 'Купить корм для кота',
            'data' => 'нет',
            'category' => 'Домашние дела',
            'complete' => 'нет',
    ],
    [
        'task' => 'Заказать пиццу',
            'data' => 'нет',
            'category' => 'Домашние дела',
            'complete' => 'нет',
    ]

        ];
function calculationTask (array $taskList, $projectName) {
    $countItem = 0;
    foreach ($taskList as $key => $val){
                if($val['category'] == $projectName){                   
                   $countItem++;
        }
    }
    return $countItem;
}


$content_page = include_template('index.php', ['tasks' => $tasks, 'show_complete_tasks' => $show_complete_tasks]);
$layout_content = include_template('layout.php',[
    'projects' => $projects,
    'tasks' => $tasks,
    'content' => $content_page,
    'title' => "Дела в порядке"
]);

print ($layout_content);


?>
