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

//Стиль
echo "<link rel='stylesheet' href='../inc/adminStyle.css'>";

//Подключение БД
require 'mysql.php';

$author_row = null;
$authorResQuer = $link->query("SELECT * FROM admin_account where login =" . $_SESSION['login'] . ";");

$sql = 'SELECT admin_privilege_num FROM admin_account WHERE login="' . $author . '"';
$admin_priv_res_query = $link->query($sql)->fetch_all(MYSQLI_ASSOC);


//Если переменная Name передана
if (isset($_GET["name"])) {
    //Если это запрос на обновление, то обновляем
    if (isset($_GET['red_id'])) {
        $redact_id = $_GET['red_id'];
        $result = mysqli_query($link, "UPDATE item SET name = '{$_GET['name']}',`price` = '{$_GET['price']}',`isShown` = '{$_GET['isShown']}', `picture_url` = '{$_GET['picture_url']}', `category_id` = '{$_GET['cat_id']}', `desc` = '{$_GET['desc']}' WHERE item_id={$_GET['red_id']}");
        $link->query("INSERT INTO `edit_journal` (`item_id`, `author_login` , `description`, `date_of_edit`) VALUES ('{$redact_id}', '{$author}',  'Изменение',  '" . date('Y-m-d H:i:s') . "')");
    } else {
        //Иначе вставляем данные, подставляя их в запрос
        $result = mysqli_query($link, "INSERT INTO `item` (`name`, `price` , `isShown`, `picture_url`, 'category_id', 'desc') VALUES ('{$_GET['name']}', '{$_GET['price']}',  '{$_GET['isShown']}',  '{$_GET['picture_url']}', '{$_GET['cat_id']}', '{$_GET['desc']}')");
        $redact_id_query = mysqli_query($link, "SELECT item_id FROM item WHERE name=" . $_GET['name']);
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

if (isset($_GET['del_id'])) { //проверяем, есть ли переменная
    //удаляем строку из таблицы
    $result = mysqli_query($link, "DELETE FROM item WHERE `item_id`={$_GET['del_id']}");
    if ($result) {
        echo "<p>Товар удален.</p>";
    } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
    }
}

//Если передана переменная red_id, то надо обновлять данные. Для начала достанем их из БД
if (isset($_GET['red_id'])) {
    $result = mysqli_query($link, "SELECT * FROM item WHERE `item_id`={$_GET['red_id']}");
    $product = mysqli_fetch_array($result);
}
?>

<h1 class="adminMenuDetails">Изменение товаров</h1>
<form action="" method="get">
    <table>
        <tr>
            <td>Наименование:</td>
            <td><input type="text" name="name" value="<?= isset($_GET['red_id']) ? $product['name'] : ''; ?>"></td>
        </tr>
        <tr>
            <td>Цена:</td>
            <td><input type="text" name="price"
                       value="<?= isset($_GET['red_id']) ? $product['price'] : ''; ?>"> руб.
            </td>
        </tr>
        <tr>
            <td>Показывать ли:</td>
            <td><input type="text" name="isShown"
                       value="<?= isset($_GET['red_id']) ? $product['isShown'] : ''; ?>">, где 0 - не показывать
            </td>
        </tr>
        <tr>
            <td>Путь к картинке:</td>
            <td><input type="text" name="picture_url"
                       value="<?= isset($_GET['red_id']) ? $product['picture_url'] : ''; ?>"></td>
        </tr>
        <tr>
            <td>Номер категории:</td>
            <td><input type="text" name="cat_id"
                       value="<?= isset($_GET['red_id']) ? $product['category_id'] : ''; ?>"></td>
        </tr>
        <tr>
            <td>Описание:</td>
            <td><input type="" name="desc"  required size="70"
                       value="<?= isset($_GET['red_id']) ? $product['desc'] : ''; ?>"></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="OK"></td>
        </tr>
    </table>
</form>
    <?php
    $result = mysqli_query($link, 'SELECT * FROM item ORDER BY category_id, price');
    $mediatorCatName = "";
    while ($raw = mysqli_fetch_array($result)) {
        $currCatName = $link->query("SELECT category_name FROM category WHERE category_id =".$raw['category_id'])->fetch_all(MYSQLI_ASSOC);
        if ($currCatName[0]['category_name']<>$mediatorCatName){
            $mediatorCatName = $currCatName[0]['category_name'];
            echo "</table></details>";

            //Two lines of code to stay chosen item category details opened
            $cat_id_of_Opened = isset($_GET['red_id']) ? $link->query("SELECT category_id FROM item WHERE item_id =".$_GET['red_id'])->fetch_all(MYSQLI_ASSOC) : null;
            echo (isset($_GET['red_id']) and $raw['category_id'] == $cat_id_of_Opened[0]['category_id']) ? "<details open>" : "<details>";

            echo "<summary>".$currCatName[0]['category_name']."</summary>";
            echo "<table border='1'>
<table>
    <tr>
        <td>Название</td>
        <td>Цена</td>
        <td>Показывать ли</td>
        <td>Путь к картинке</td>
        <td>Описание</td>
        <td>Редактирование</td>
        <td></td>
    </tr>";
        }
        echo '<tr>';
        $isShownInterpreted = $raw['isShown'] == 1 ? "Да" : "Нет";
        echo"<td>{$raw['name']}</td>" .
            "<td>{$raw['price']}</td>" .
            "<td>{$isShownInterpreted}</td>" .
            "<td>{$raw['picture_url']}</td>" .
            "<td>{$raw['desc']}</td>" .
            "<td><a href='?red_id={$raw['item_id']}'>Изменить</a></td>";
        if ($admin_priv_res_query[0]['admin_privilege_num'] == 15) {
            echo "<td><a href='?del_id={$raw['item_id']}'>Удалить</a></td>";
        } else echo "<td></td>";
        echo "</tr>";
    }
    echo "</table></details>";
    ?>
<p><a href="?add=new">Добавить новый товар</a></p>


</body>
</html>












<!--РЕДАКТИРОВАНИЕ КАТЕГОРИЙ-->
<?php

if (isset($_GET["category_name"])) {
    //Если это запрос на обновление, то обновляем
    if (isset($_GET['red_cat_id']) and $_GET['red_cat_id']<>'') {
        $redact_id = $_GET['red_cat_id'];
        $catResult = mysqli_query($link, "UPDATE category SET category_name = '{$_GET['category_name']}', `picture_url` = '{$_GET['cat_picture_url']}', `order_numero` = '{$_GET['order_numero']}' WHERE category_id={$_GET['red_cat_id']}");
//        $link->query("INSERT INTO `edit_journal` (`item_id`, `author_login` , `description`, `date_of_edit`) VALUES ('{category_id}', '{$author}',  'Изменение категории',  '" . date('Y-m-d H:i:s') . "')");
    } else {
        //Иначе вставляем данные, подставляя их в запрос
        $catResult = mysqli_query($link, "INSERT INTO `category` (`category_name`, `picture_url` , `order_numero`) VALUES ('{$_GET['category_name']}', '{$_GET['cat_picture_url']}',  '{$_GET['order_numero']}')");
        //$redact_id_query = mysqli_query($link, "SELECT item_id FROM item WHERE name=" . $_GET['category_name']);
        //Проблема с налл. Запрос возвращается пустой
        /*
        $redact_id = null;
        if ($redact_id_query) {
            foreach ($redact_id_query as $row) {
                $redact_id = $row['item_id'];
            }
        }
        $link->query("INSERT INTO `edit_journal` (`item_id`, `author_login` , `description`, `date_of_edit`) VALUES ('{category_id}', '{$author}',  'Добавление категории',  '" . date('Y-m-d H:i:s') . "')");
        */
    }

    //Если вставка прошла успешно
    if ($catResult) {
        echo '<p>Успешно!</p>';
    } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
    }
}

if (isset($_GET['del_cat_id'])) { //проверяем, есть ли переменная
    //удаляем строку из таблицы
    $result = mysqli_query($link, "DELETE FROM category WHERE `category_id`={$_GET['del_cat_id']}");
    if ($result) {
        echo "<p>Категория удалена.</p>";
    } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
    }
}

if (isset($_GET['red_cat_id'])) {
    $result = mysqli_query($link, "SELECT * FROM category WHERE `category_id`={$_GET['red_cat_id']}");
    $category = mysqli_fetch_array($result);
}
?>

<?php

if (isset($_GET['red_cat_id']) or isset($_GET['del_cat_id']) or isset($_GET['addCat']) or isset($_GET['red_cat_id'])) {
    echo "<details open class='adminMenuDetails'> <summary>Изменение категорий</summary>";
}
else{
    echo "<details class='adminMenuDetails'> <summary>Изменение категорий</summary>";
}
?>

<form action="" method="get">
    <table>
        <tr>
            <td>id:</td>
            <td><input readonly type="text" name="red_cat_id" value="<?= isset($_GET['red_cat_id']) ? $category['category_id'] : ''; ?>"></td>
        </tr>
        <tr>
            <td>Наименование:</td>
            <td><input type="text" name="category_name" value="<?= isset($_GET['red_cat_id']) ? $category['category_name'] : ''; ?>"></td>
        </tr>
        <tr>
            <td>Путь к картинке:</td>
            <td><input type="text" name="cat_picture_url"
                       value="<?= isset($_GET['red_cat_id']) ? $category['picture_url'] : ''; ?>"></td>
        </tr>
        <tr>
            <td>Порядок:</td>
            <td><input type="" name="order_numero"  required size="70"
                       value="<?= isset($_GET['red_cat_id']) ? $category['order_numero'] : ''; ?>"></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="OK"></td>
        </tr>
    </table>
</form>
<?php
echo "
<table>
    <tr>
        <td>id</td>
        <td>Название</td>
        <td>Путь к картинке</td>
        <td>Порядок</td>
        <td>Редактирование</td>
        <td></td>
    </tr>";
$result = mysqli_query($link, 'SELECT * FROM category ORDER BY order_numero');
$mediatorCatName = "";
while ($raw = mysqli_fetch_array($result)) {
    echo '<tr>';
    echo"<td>{$raw['category_id']}</td>" .
        "<td>{$raw['category_name']}</td>" .
        "<td>{$raw['picture_url']}</td>" .
        "<td>{$raw['order_numero']}</td>" .
        "<td><a href='?red_cat_id={$raw['category_id']}'>Изменить</a></td>";
    if ($admin_priv_res_query[0]['admin_privilege_num'] == 15) {
        echo "<td><a href='?del_cat_id={$raw['category_id']}'>Удалить</a></td>";
    } else echo "<td></td>";
    echo "</tr>";
}
echo "</table></details>";
?>
<p><a href="?addCat=new">Добавить новую категорию</a></p>

<?php

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