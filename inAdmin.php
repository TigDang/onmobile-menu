<!doctype html>
<html lang="ru">
<head>
    <title>Админ-панель</title>
    <!--<link rel='stylesheet' href='..\inc/style.css'>-->
</head>
<body>

<a href="main_menu.php">Обратно</a>
<?php
session_start();
if (isset($_SESSION["login"])) {
    $author = $_SESSION['login'];
    echo "<br>Вы вошли как " . $author;
} else {
    $new_url = 'admin.php';
    header('Location: ' . $new_url);
}

//Подключение БД
require 'mysql.php';

$author_row = null;
$authorResQuer = $link->query("SELECT * FROM admin_account where login =" . $_SESSION['login'] . ";");

$sql = 'SELECT admin_privilege_num FROM admin_account WHERE login="' . $author . '"';
$admin_priv_res_query = $link->query($sql)->fetch_all(MYSQLI_ASSOC);


//Если переменная Name передана
if (isset($_POST["name"])) {
    //Если это запрос на обновление, то обновляем
    if (isset($_POST['red_id'])) {
        $redact_id = $_POST['red_id'];
        $result = mysqli_query($link, "UPDATE item SET name = '{$_POST['name']}',`price` = '{$_POST['price']}',`isShown` = '{$_POST['isShown']}', `picture_url` = '{$_POST['picture_url']}', `category_id` = '{$_POST['cat_id']}', `desc` = '{$_POST['desc']}' WHERE item_id={$_POST['red_id']}");
        $link->query("INSERT INTO `edit_journal` (`item_id`, `author_login` , `description`, `date_of_edit`) VALUES ('{$redact_id}', '{$author}',  'Изменение',  '" . date('Y-m-d H:i:s') . "')");
    } else {
        //Иначе вставляем данные, подставляя их в запрос
        $result = mysqli_query($link, "INSERT INTO `item` (`name`, `price` , `isShown`, `picture_url`, 'category_id', 'desc') VALUES ('{$_POST['name']}', '{$_POST['price']}',  '{$_POST['isShown']}',  '{$_POST['picture_url']}', '{$_POST['cat_id']}', '{$_POST['desc']}')");
        $redact_id_query = mysqli_query($link, "SELECT item_id FROM item WHERE name=" . $_POST['name']);
        //Проблема с налл. Запрос возвращается пустой
        $redact_id = null;
        if ($redact_id_query) {
            foreach ($redact_id_query as $row) {
                $redact_id = $row['item_id'];
            }
        }
        $link->query("INSERT INTO `edit_journal` (`item_id`, `author_login` , `description`, `date_of_edit`) VALUES ('{$redact_id}', '{$author}',  'Изменение',  '" . date('Y-m-d H:i:s') . "')");
    }

    //Если вставка прошла успешно
    if ($result) {
        echo '<p>Успешно!</p>';
    } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
    }
}

if (isset($_POST['del_id'])) { //проверяем, есть ли переменная
    //удаляем строку из таблицы
    $result = mysqli_query($link, "DELETE FROM item WHERE `item_id`={$_POST['del_id']}");
    if ($result) {
        echo "<p>Товар удален.</p>";
    } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
    }
}

//Если передана переменная red_id, то надо обновлять данные. Для начала достанем их из БД
if (isset($_POST['red_id'])) {
    $result = mysqli_query($link, "SELECT * FROM item WHERE `item_id`={$_POST['red_id']}");
    $product = mysqli_fetch_array($result);
}
?>

<h1>Изменение товаров</h1>
<form action="" method="post">
    <table>
        <tr>
            <td>Наименование:</td>
            <td><input type="text" name="name" value="<?= isset($_POST['red_id']) ? $product['name'] : ''; ?>"></td>
        </tr>
        <tr>
            <td>Цена:</td>
            <td><input type="text" name="price" size="10"
                       value="<?= isset($_POST['red_id']) ? $product['price'] : ''; ?>"> руб.
            </td>
        </tr>
        <tr>
            <td>Показывать ли:</td>
            <td><input type="text" name="isShown" size="1"
                       value="<?= isset($_POST['red_id']) ? $product['isShown'] : ''; ?>">, где 0 - не показывать
            </td>
        </tr>
        <tr>
            <td>Путь к картинке:</td>
            <td><input type="text" name="picture_url" size="30"
                       value="<?= isset($_POST['red_id']) ? $product['picture_url'] : ''; ?>"></td>
        </tr>
        <tr>
            <td>Номер категории:</td>
            <td><input type="text" name="cat_id" size="1"
                       value="<?= isset($_POST['red_id']) ? $product['category_id'] : ''; ?>"></td>
        </tr>
        <tr>
            <td>Описание:</td>
            <td><input type="text" name="desc" size="30"
                       value="<?= isset($_POST['red_id']) ? $product['desc'] : ''; ?>"></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="OK"></td>
        </tr>
    </table>
</form>
<table border='1'>
    <tr>
        <td>Название</td>
        <td>Цена</td>
        <td>Показывать ли</td>
        <td>Путь к картинке</td>
        <td>Описание</td>
        <td>Редактирование</td>
        <td></td>
    </tr>
    <?php
    $result = mysqli_query($link, 'SELECT * FROM item ORDER BY price');
    while ($raw = mysqli_fetch_array($result)) {
        $isShownInterpreted = $raw['isShown'] == 1 ? "Да" : "Нет";
        echo '<tr>' .
            "<td>{$raw['name']}</td>" .
            "<td>{$raw['price']}</td>" .
            "<td>{$isShownInterpreted}</td>" .
            "<td>{$raw['picture_url']}</td>" .
            "<td>{$raw['desc']}</td>" .
            "<td><a href='?red_id={$raw['item_id']}'>Изменить</a></td>";
        if ($admin_priv_res_query[0]['admin_privilege_num'] == 15) {
            echo "<td><a href='?del_id={$raw['item_id']}'>Удалить</a></td>";
        } else echo "<td>    </td>";
        echo "</tr>";
    }
    ?>
</table>
<p><a href="?add=new">Добавить новый товар</a></p>


</body>
</html>

<?php


if ($admin_priv_res_query[0]['admin_privilege_num'] == 15) {
    createNewAccount();
    showEditJournal();
}

function showEditJournal()
{
    require 'mysql.php';
    echo "<h1>История изменений</h1> <table border='1'><tr>
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
    echo "</table>";
}

function createNewAccount()
{
    require 'mysql.php';
    echo "<h1>Администраторы</h1><table border='1'><tr>
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

    echo "</table>";

    //Новый пользователь
    if (isset($_POST['new_login'])){
        $Addresult = $link->query("INSERT INTO admin_account(LOGIN, PASSWORD, ADMIN_PRIVILEGE_NUM) values ('{$_POST['new_login']}','{$_POST['new_password']}','{$_POST['new_priv']}');");
        if ($Addresult) {
            echo "<p>Пользователь {$_POST['new_login']} добавлен.</p>";
        } else {
            echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
        }
    }

    //Удаление пользователя
    if (isset($_POST['del_login'])){
        $Delresult = $link->query("DELETE FROM admin_account WHERE login='{$_POST['del_login']}';");
        if ($Delresult) {
            echo "<p>Пользователь удален.</p>";
        } else {
            echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
        }
    }
}