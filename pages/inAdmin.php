<?php

echo "<title>Админ-панель</title>";
echo '<a href="main_menu.php">Обратно</a>';

//Стиль
echo "<link rel='stylesheet' href='../inc/adminStyle.css'>";

//Подключение БД
require 'mysql.php';

//Проверка сессии
include "admin/checkSess.php";

//Запуск редактора итемов
require "admin/itemEditor.php";

//Редактор категорий
require "admin/catEditor.php";


echo "Не работает добавление";
if ($admin_priv_res_query[0]['admin_privilege_num'] == 15) {
    createNewAccount();
    showEditJournal();
}

function showEditJournal()
{
    require 'mysql.php';
    echo "<details class='adminMenuDetails'><summary>История изменений</summary> <table><tr>
        <td>edit_id</td>
        <td>Логин автора</td>
        <td>Товар</td>
        <td>Описание изменения</td>
        <td>Дата</td>
    </tr>";
    $result = $link->query('SELECT * FROM edit_history_view ORDER BY date_of_edit DESC');
    while ($raw = mysqli_fetch_array($result)) {
        echo '<tr>' .
            "<td>{$raw['edit_id']}</td>" .
            "<td>{$raw['author_login']}</td>" .
            "<td>{$raw['name']}</td>" .
            "<td>{$raw['description']}</td>" .
            "<td>{$raw['date_of_edit']}</td>" .
            '</tr>';
    }
    echo "</table></details>";
}

function createNewAccount()
{
    require 'mysql.php';
    echo "<details class='adminMenuDetails'><summary>Администраторы</summary><table><tr>
        <td>Логин</td>
        <td>Пароль</td>
        <td>Привилегия</td>
    </tr>";
    $admins = $link->query('SELECT * FROM admin_account');
    while ($user = mysqli_fetch_array($admins)) {
        echo '<tr>' .
            "<td>{$user['login']}</td>" .
            "<td>{$user['password']}</td>" .
            "<td>{$user['admin_privilege_num']}</td>";
            if ($user['login'] <> $_SESSION['login']) {
                echo "<td><a href='?del_login={$user['login']}'>Удалить</a></td>";
            } else echo "<td>    </td>";
            echo '<tr>';
    }
    echo "<form action='' method='post'>";
        echo "<tr>";
            echo "<td><input type='text' name='new_login' value='' placeholder='Логин нового админа'></td>";
            echo "<td><input type='text' name='new_password' value='' placeholder='Пароль'></td>";
            echo "<td><input type='text' name='new_priv' value='1' placeholder='Привилегия'></td>";
            echo "<td><input type='submit' value='Добавить'></td>";
        echo "</tr>";
    echo "</form>";

    echo "</table></details>";

    //Новый пользователь
    if (isset($_GET['new_login'])){
        $Addresult = $link->query("INSERT INTO admin_account(LOGIN, PASSWORD, ADMIN_PRIVILEGE_NUM) values ('{$_GET['new_login']}','{$_GET['new_password']}','{$_GET['new_priv']}');");
        if ($Addresult) {
            echo "<p>Пользователь {$_GET['new_login']} добавлен.</p>";
        } else {
            echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
        }
    }

    //Удаление пользователя
    if (isset($_GET['del_login'])){
        $Delresult = $link->query("DELETE FROM admin_account WHERE login='{$_GET['del_login']}';");
        if ($Delresult) {
            echo "<p>Пользователь удален.</p>";
        } else {
            echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
        }
    }
}