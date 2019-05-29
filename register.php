<?php
require_once('system/init.php');

//Если в массиве user есть данные о пользователи из сессии открываем доступ к странице, если нет то редирект на гостевую
if (!empty($user)) {
    header("Location: /");
    exit();
}

//Объявляем переменные
$keys = ['email', 'password', 'name'];
$errors = [];
$data = [];

//Проверяем отправлена ли нам форма для регистрации
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Проверяем заполнены обязательные поля, если нет записываем в массив  Error
    foreach ($keys as $val) {
        if (isset($_POST[$val]) AND !empty(trim($_POST[$val]))) {
            $data[$val] = mysqli_real_escape_string($link, trim($_POST[$val]));
        } else {
            $errors[$val] = "Это поле обязательно для заполнения";
        }
    }

    //Проверяем корректность email если ошибок до этого не было
    if (!isset($errors['email']) AND !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Электронная почта введена неверна";
    }

    //Если ошибок нет, проверяем нет ли уже такого зарегестрированого email
    if (!isset($errors['email'])) {
        $sql_email = "SELECT email FROM users WHERE email ='" . $data['email'] . "'";
        $res = mysqli_query($link, $sql_email);
        $email = mysqli_fetch_all($res, MYSQLI_ASSOC);
        if ($email) {
            $errors['email'] = "Пользователь с таким e-mail уже зарегистрирован";
        }
    }

    //Если ошибок в имени нет, проверяем длину имени
    if (empty($errors['name']) AND strlen($data['name']) > 60) {
        $errors['name'] = "Cлишком длинное имя";
    }

    //Если ошибок нет, то хэшируем пароль и добавляем данные в БД
    if (empty($errors)) {
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (email, password, name) VALUES ('" . $data['email'] . "', '$password' ," . "'" . $data['name'] . "')";
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
$content = include_template('reg.php', ['data' => $data, 'errors' => $errors]);
$layout_content = include_template('layout-auth.php', ['content' => $content, 'title' => "Регистрация"]);
print ($layout_content);
