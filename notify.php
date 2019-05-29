<?php
require_once 'system/init.php';
require_once 'vendor/autoload.php';

//Объявляем переменные
$msg = "";
$msg_content = "";

//Получаем задачи на сегодня
$sql = "SELECT u.id_user AS id, email, u.name AS name FROM tasks t LEFT JOIN users u ON t.id_user=u.id_user WHERE status = 0 AND term = CURDATE() GROUP BY u.id_user";
$res = mysqli_query($link, $sql);
$users_current_date = mysqli_fetch_all($res, MYSQLI_ASSOC);

//Если задачи есть формируем и отправляпем письмо
if (!empty($users_current_date)) {

	//Создаем объект transport для отправки письма
    $transport = new Swift_SmtpTransport('phpdemo.ru', 25);
    $transport->setUsername('keks@phpdemo.ru');
    $transport->setPassword('htmlacademy');
    $mailer = new Swift_Mailer($transport);

    //Формирование самого письма
    foreach ($users_current_date as $key => $value) {

    	//Получаем задачи для определенного пользователя
        $sql_tasks = "SELECT * FROM tasks WHERE status = 0 AND term = CURDATE() AND id_user=" . $value['id'];
        $result = mysqli_query($link, $sql_tasks);
        $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);

        //Меняем имя для каждого пользователя
        $msg_content = "Уважаемый(-ая) " . $value['name'] . " у вас запланирована задача(-и) ";
        //Обнуляем переменную для работы
        $msg = "";

        //Формируем список задач для пользователя
        foreach ($tasks as $k => $task) {
            $msg .= "<br>" . $task['name'] . " на дату: " . correct_visual_date($task['term']) . " ";
        }

        //Формируем шаблон письма
        $message = new Swift_Message('Уведомление от сервиса «Дела в порядке»');
        $message->setFrom(['keks@phpdemo.ru' => 'keks@phpdemo.ru']);
        $message->setTo([$value['email'] => $value['name']]);
        $message->setBody($msg_content . $msg, 'text/html');

        //Отправка письма
        $result = $mailer->send($message);
    }
}
