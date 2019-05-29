<?php
require_once('system/init.php');

//Если в массиве user есть данные о пользователи из сессии открываем доступ к странице, если нет то редирект на гостевую
if (!empty($user)) {
    header("Location: /");
    exit();
}

//Объявляем переменнные
$keys = ['email', 'password'];
$errors = [];
$data = [];

//Проверяем отправлена ли нам форма для авторизации
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //проверка заполненны все обязательне поля
    foreach ($keys as $val) {
        if (isset($_POST[$val]) AND !empty(trim($_POST[$val]))) {
            $data[$val] = mysqli_real_escape_string($link, trim($_POST[$val]));
        } else {
            $errors[$val] = "Это поле обязательно для заполнения";
        }
    }

    //Если поле email заполенные проверяем корректность email
    if (empty($errors['email'])) {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "E-mail введён некорректно";

          //если email заполнен корректно делаем запрос к БД
        } else {
            $sql = "SELECT * FROM users WHERE email='" . $data['email'] . "'";
            $res = mysqli_query($link, $sql);
            $verify = mysqli_fetch_assoc($res);
        }
    }

    //Проверяем есть ли мыло в базе
    if (empty($errors['email']) AND empty($verify)) {
        $errors['email'] = "E-mail в базе не найден";
    }

    //Проверяем пароль
    if (empty($errors)) {
        if (!password_verify($data['password'], $verify['password'])) {
            $errors['password'] = "Пароль не подходит к данному email";
        }
    }

    //Если ошибок нет, добавляем в сессию id пользователя и редирект на главную
    if (empty($errors)) {
        $_SESSION['user_id'] = intval($verify['id_user']);
        header("Location: /");
        exit();
    }
}
//Подключение шаблонов и вывод страницы
$content = include_template('auth.php', ['data' => $data, 'errors' => $errors]);
$layout_content = include_template('layout-auth.php', ['content' => $content, 'title' => "Вход"]);
print ($layout_content);
