<?php
require_once 'system/init.php';

//Если в массиве user есть данные о пользователи из сессии открываем доступ к странице, если нет то редирект на гостевую
if (empty($user)) {
    header("Location: /");
    exit();
}

//Проверяем отправлена ли нам форма для добавления проекта
$errors = "";
$data = "";

//Проверяем отправлена ли нам форма для добавления задачи
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Проверяем заполнены обязательные поля, если нет записываем в массив  Error
    if (isset($_POST['name']) AND !empty(trim($_POST['name']))) {
        $data = mysqli_real_escape_string($link, trim($_POST['name']));
    } else {
        $errors = "Это поле обязательно для заполнения";
    }

    //Если в именни проекта нет ошибок, проверяем длину именни
    if (empty($errors) AND strlen($data) > 60) {
        $errors = "Cлишком длинное имя проекта, максимум 60 символов";
    }

    //Если ошибок нет, проверяем есть ли у пользователя такое имя проекта
    if (empty($errors)) {
        $sql_name_project = "SELECT name FROM projects WHERE id_user='$user_id' AND name='$data'";
        $result = mysqli_query($link, $sql_name_project);
        $name_project = mysqli_fetch_assoc($result);
        if (!empty($name_project)) {
            $errors = "У Вас уже есть проект с таким именем";
        }
    }

    //Если с ошибками всё норм, добавляем проект в БД
    if (empty($errors)) {
        $sql = "INSERT INTO projects (name, id_user) VAlUES ('$data', '$user_id')";
        $res = mysqli_query($link, $sql);

        if ($res) {
            header("Location: /");
            exit();
        } else {
            print_r(mysqli_error($link));
        }
    }
}

//Подключаем шаблоны
$content = include_template('project.php', ['errors' => $errors, 'data' => $data]);
$layout_content = include_template('layout.php', [
    'projects' => $projects,
    'content' => $content,
    'title' => $title,
    'active' => $active_project_id,
    'name_user' => $user['name']
]);
print ($layout_content);