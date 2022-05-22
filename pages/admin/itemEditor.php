<?php
//Если переменная Name передана
if (isset($_GET["name"])) {
    //Если это запрос на обновление, то обновляем
    if (isset($_GET['red_id']) and $_GET['red_id']<>'') {
        $redact_id = $_GET['red_id'];
        $result = mysqli_query($link, "UPDATE item SET name = '{$_GET['name']}',`price` = '{$_GET['price']}',`isShown` = '{$_GET['isShown']}', `picture_url` = '{$_GET['picture_url']}', `category_id` = '{$_GET['cat_id']}', `desc` = '{$_GET['desc']}' WHERE item_id={$_GET['red_id']}");
        $link->query("INSERT INTO `edit_journal` (`item_id`, `author_login` , `description`, `date_of_edit`) VALUES ('{$redact_id}', '{$author}',  'Изменение',  '" . date('Y-m-d H:i:s') . "')");
    } else {
        $sql  = "INSERT INTO `item` (`name`, `price` , `isShown`, `picture_url`, `category_id`, `desc`) VALUES ('{$_GET['name']}', '{$_GET['price']}',  '{$_GET['isShown']}',  '{$_GET['picture_url']}', '{$_GET['cat_id']}', '{$_GET['desc']}');";
        //Иначе вставляем данные, подставляя их в запрос
        $result = mysqli_query($link, $sql);
    }
    //Если вставка прошла успешно
    if ($result) {
        echo '<p>Успешно!</p>';
    } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
    }
    unset($_GET);
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
            <td>id:</td>
            <td><input readonly type="text" name="red_id" value="<?= isset($_GET['red_id']) ? $product['item_id'] : ''; ?>"></td>
        </tr>
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
            <td><input type="text" name="desc"  required size="70"
                       value=" <?= isset($_GET['red_id']) ? $product['desc'] : ''; ?>"></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="OK"></td>
        </tr>
    </table>
</form>
<?php
$result = mysqli_query($link, 'SELECT * FROM item ORDER BY category_id, price');
$mediatorCatName = "";
$otherCat = "";
while ($raw = mysqli_fetch_array($result)) {
    $currCatName = $link->query("SELECT category_name FROM category WHERE category_id =".$raw['category_id'])->fetch_all(MYSQLI_ASSOC);

    if (!isset($currCatName[0]['category_name'])) $otherCat = "Вне категорий, не отображаются";

    //Если категория меняется при переборе
    if ($currCatName[0]['category_name']<>$mediatorCatName){
        $mediatorCatName = $currCatName[0]['category_name'] . $otherCat;
        //Закрываем прошлую таблицу и закрываем details
        echo "</table></details>";

        //Открываем новый details (и если его айди указан в гет-запросе, то делаем открытым) и новую таблицу
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
    //Формируем строку, повествующую об итеме
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