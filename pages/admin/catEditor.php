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
    unset($_GET);
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

if (isset($_GET['red_cat_id']) or isset($_GET['del_cat_id']) or isset($_GET['addCat'])) {
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
            <td><input type="text" name="order_numero"  required size="70"
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