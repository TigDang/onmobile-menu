<?php

echo "<title>Категории</title>";

//Подключаем БД
require 'mysql.php';
//Подключаем стили
echo "<link rel='stylesheet' href='inc/style.css'>";

//Делаем меню основное
$result = $link->query("SELECT * FROM category ORDER BY order_numero ASC");
echo "<a href='..\main_menu.php'><img id=\"label\" src=\"..\gfx/label.jpg\"></a>";
echo "<h1>Меню</h1>";
echo "<div id='container'>";
foreach ($result as $row) {
    //Если не существует картинки по указанному адресу
    if (!file_exists($row['picture_url']))
        //Картинка по-умолчанию
        $row['picture_url'] = "gfx/table.jpg";
    echo "<div style=\"cursor: pointer;\" onclick=\"window.location='inner_menu.php/?category_id=" . $row['category_id'] . "';\" class='item'> <br><img src='" . $row['picture_url'] . "'><span class='cat_label'>" . $row['category_name'] . "</span></img> </div>";
}
echo "</div>";


//Боковое меню
require "right_menu.php";
