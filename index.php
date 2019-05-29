<?php
require_once('system/init.php');

//Если в массиве user есть данные о пользователи из сессии открываем доступ к главной странице, если нет то гостевую
if (!empty($user)) {

	//Объявляем переменные
    $tasks = [];
    $show_complete_tasks = 0;
    $date_select = "";
    $search = "";

    //Достаем значение из куки показывать выполненные задачи или нет, если кука существует
    if (isset($_COOKIE["show"])) {
        $show_complete_tasks = intval($_COOKIE["show"]);
    }

    //проверка нажатия на чекбокс показать выполненные
    if (isset($_GET['show_completed'])) {
    	
    	//присваем значение переменной из глобальной перменной GET
        $show_complete_tasks = intval($_GET['show_completed']);
        
        //Записываем в куку
        setcookie("show", $show_complete_tasks, time() + 86400, '/');
    }

    //проверка нажатия на чекбокс выполнить задачу
    if (isset($_GET['task_id'])) {
    	
    	//получаем данные из БД по id задачи
        $task_id = intval($_GET['task_id']);
        $sql = "SELECT * FROM tasks WHERE id_task = '$task_id' AND id_user = '$user_id'";
        $res = mysqli_query($link, $sql);
        $task_complete = mysqli_fetch_assoc($res);

        //проверяем на существование такого id в базе если нет подключаем шаблон с ошибкой
        if (empty($task_complete)) {
            error_404($projects, $title, $user_id);
        }

        //делаем конвертацию, если была выполнена, стала невыполнена и наоборот
        if ($task_complete['status'] === "0") {
            $status = 1;
        } else {
            $status = 0;
        }

        //Обновляем задачу в БД
        $sql_update = "UPDATE tasks SET status = '$status' WHERE id_task = '$task_id' AND id_user = '$user_id'";
        $result = mysqli_query($link, $sql_update);
    }

    //Фильтр по проектам
    if (isset($_GET['id'])) {

    	//Записываем id активного проекта
        $active_project_id = intval($_GET['id']);

        //Получаем проект из БД по id
        $sql_project_id = "SELECT project_id FROM projects WHERE project_id= " . $active_project_id . " AND id_user= " . $user_id;
        $res = mysqli_query($link, $sql_project_id);
        $project = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    //Проверяем, что такой проект существует по выбранному id и выводим задачи для него, если нет подключаем ошибку
    if (empty($project) AND isset($_GET['id'])) {
        error_404($projects, $title, $user);
    } else {
        $tasks = get_task($link, $active_project_id, $user_id, $show_complete_tasks);
        $content = include_template('index.php', [
              'tasks' => $tasks, 
              'show_complete_tasks' => $show_complete_tasks, 
              'active' => $active_project_id
            ]);

    }

    //Фильтр по дате
    if (isset($_GET['date'])) {

    	//Получуаем информацию на какую дату надо фильтровать из глобального массива
        $date_select = mysqli_real_escape_string($link, $_GET['date']);

        //получаем задачи по заданному фильтру
        $tasks = filter_tasks($date_select, $user_id, $link, $active_project_id);

        //Если передано в глобальный массив некорректное значение подключаем ошибку, если всё норм вывыодим задачи по фильтру
        if ($date_select AND $tasks == "error") {
            error_404($projects, $title, $user);
        } else {
            $content = include_template('index.php', [
                'tasks' => $tasks,
                'show_complete_tasks' => $show_complete_tasks,
                'date_select' => $date_select,
                'active' => $active_project_id
            ]);
        }

    }

    //Система поиска
    if (isset($_GET['search'])) {

    	//Записываем в переменную данные переданные пользователем
        $search = mysqli_real_escape_string($link, trim($_GET['search']));

        //Формируем запрос в БД
        $sql_find = "SELECT * FROM tasks WHERE MATCH(name) AGAINST('$search') AND id_user='$user_id'";

        //если не поставлена галочка показывать выполненные поиск производим, только по невыполненным
        if ($show_complete_tasks == 0) {
            $sql_find .= " AND status=0";
        }

        //Получаем задачи по поиску
        $res = mysqli_query($link, $sql_find);
        $tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);

        //Выводим задачи если нашли, если нет подключаем шаблон ничего не найдено.
        if (empty($tasks)) {
            $content = include_template('not-find.php');
        } else {
            $content = include_template('index.php', [
                'tasks' => $tasks,
                'show_complete_tasks' => $show_complete_tasks,
                'active' => $active_project_id
            ]);
        }

    }

    //Подключаем layout
    $layout_content = include_template('layout.php', [
        'projects' => $projects,
        'tasks' => $tasks,
        'content' => $content,
        'title' => $title,
        'active' => $active_project_id,
        'name_user' => $user['name'],
        'date_select' => $date_select
    ]);
} else {
    $layout_content = include_template('guest.php', ['title' => $title]);
}

//Показываем html страницу
print ($layout_content);
?>