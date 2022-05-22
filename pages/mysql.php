<?php
$host = 'localhost';  // Хост, у нас все локально
$user = 'mysql_waitor';    // Имя созданного вами пользователя
$pass = 'LJgmcyjt'; // Установленный вами пароль пользователю
$db_name = 'f0669044_mysql';   // Имя базы данных
global $link;
$link = mysqli_connect($host, $user, $pass, $db_name); // Соединяемся с базой
if (!$link) {
    echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
    exit;
}