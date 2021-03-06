<?php

echo "<a href='..\pages\main_menu.php'><img id=\"label\" src=\"..\gfx/label.png\"></a>";

echo "<link rel='stylesheet' href='..\inc/style.css'>";


//Подключаемся к БД
require 'mysql.php';


//Определяем категорию
if (isset($_POST['category_id'])) {
    $itemsOfChoosedCategory = $link->query("SELECT * FROM item WHERE category_id = " . $_POST['category_id'] . " ORDER BY price ASC");
} else
    $itemsOfChoosedCategory = $link->query("SELECT * FROM item");


//Пишем заголовок
$titleQuery = $link->query("SELECT category_name FROM category WHERE category_id = " . $_POST['category_id']);

foreach ($titleQuery as $row) {
    $title = $row["category_name"];
}

echo "<h1>" . $title . "</h1>";
echo "<title>" . $title . "</title>";


//Выводим все элементы из выбранной категории
echo "<div id='container'>";
foreach ($itemsOfChoosedCategory as $item) {
    //Проверка на флаг видимости в меню
    if ($item['isShown'] <> 0) {
//        $picture_url = "../" . $item['picture_url'];
//        //Если не существует картинки по указанному адресу
//        if (!file_exists($picture_url))
//            //Картинка по-умолчанию
//            $picture_url = "../gfx/table.jpg";
        echo "<div class='item'> <img src='" . $item['picture_url'] . "'><span class='cat-item_label'>" . $item['name'] . "</span></img><span class='priceLabel'>" . $item['price'] . "р. </span>";
        if (isset($item['desc']) and $item['desc']<>' ' and $item['desc']<>'') echo "<div class = 'desc'>".$item['desc']."</div>";
        echo "</div>";
    }


}
echo "</div>";

//Боковое меню
require "right_menu.php";
