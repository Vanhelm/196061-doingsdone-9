<?php
//Обнулеяем сессию и идём на главную страницу
session_start();
$_SESSION = [];
header("Location: /");