<?php
require_once 'system/init.php';

//Если в массиве user есть данные о пользователи из сессии открываем доступ к странице, если нет то редирект на гостевую
if (empty($user)) {
    header("Location: /");
    exit();
}

//Объявляем переменные
$keys = ['name', 'project'];
$currentDate = time() - 86400;
$errors = [];
$data = [];
$id = 0;

//Проверяем отправлена ли нам форма для добавления задачи
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Проверяем заполнены обязательные поля, если нет записываем в массив  Error
    foreach ($keys as $val) {

        if (isset($_POST[$val]) AND !empty(trim($_POST[$val]))) {
            $data[$val] = mysqli_real_escape_string($link, trim($_POST[$val]));
        } else {
            $errors[$val] = "Это поле обязательно для заполнения";
        }
    }

    //Если ошибок нет в поле name нет, проверяем длину, в случае отсутствия записываем ошибку в массив error
    if (empty($errors['name']) AND strlen($data['name']) > 60) {
        $errors['name'] = "Cлишком длинное имя задачи, максимум 60 символов";
    }

    //Если в поле project ошибок нет, проверяем есть ли в БД такой проект, в случае отсутствия записываем ошибку в массив error
    if (empty($errors['project'])) {
        $id = intval($_POST['project']);
        $sql = "SELECT project_id, name FROM projects WHERE project_id= " . $id . " AND id_user= " . $user_id;
        $res = mysqli_query($link, $sql);
        $id_project = mysqli_fetch_all($res, MYSQLI_ASSOC);
        if (empty($id_project)) {
            $errors['project'] = "так нельзя делать";
        }
    }

    //Провеяем дату, если дата заполнена
    if (!empty($_POST['date'])) {

        //Проверяем формат даты с помощью подгатовленной функции
        if (!is_date_valid($_POST['date'])) {
            $errors['date'] = "Неверный формат даты";

          //Проверяем меньше ли дата текущей
        } elseif (strtotime($_POST['date']) <= $currentDate) {
            $errors['date'] = "Дата не может быть меньше текущей";

          //если всё хорошо записываем в переменную
        } else {
            $data['date'] = "'" . $_POST['date'] . "'";
        }
      //Обнуляем переменную если дата не заполнена
    } else {
        $data['date'] = "null";
    }

    //Проверяем загружен ли файл
    if (isset($_FILES['file']) AND is_uploaded_file($_FILES['file']['tmp_name'])) {

        //Даём загрузить файл если нет ошибок в полях, если есть отменяем загрузку
        if (!empty($errors)) {
            $errors['file'] = "Заполните форму без ошибок и загрузите файл повторно!";  
        } else {
            $file_name = $_FILES['file']['name'];
            $uniq_name = uniqid($file_name);
            $file_path = $_SERVER['DOCUMENT_ROOT'] . "/uploads/";
            $data['file_url'] = "/uploads/" . $uniq_name;
            move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $uniq_name);
        }
    } else {
        $data['file_url'] = "";
    }

    //Если нет ошибок, добавляем задачу в БД и делаем редирект на главную странциу.
    if (empty($errors)) {
        $sql = "INSERT INTO tasks (name, project_id, file, id_user, term) VALUES ('" . $data['name'] . "','" . $data['project'] . "','" . $data['file_url'] . "', '$user_id'," . $data['date'] . ")";
        $result = mysqli_query($link, $sql);

        if ($result) {
            header("Location: /");
            exit();
        } else {
            print_r(mysqli_error($link));
        }
    }
}

//Подключаем шаблоны
$content = include_template('form-task.php',
    ['projects' => $projects, 'errors' => $errors, 'data' => $data, 'id' => $id]);
$layout_content = include_template('layout.php', [
    'projects' => $projects,
    'content' => $content,
    'title' => "Добавить задачу",
    'active' => $active_project_id,
    'name_user' => $user['name']
]);

//Выводим html код
print ($layout_content);
