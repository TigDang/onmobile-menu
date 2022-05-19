<?php

echo "<title>Категории</title>";

//Подключаем БД
require 'mysql.php';
//Подключаем стили
echo "<link rel='stylesheet' href='../inc/style.css'>";

//Делаем меню основное
$result = $link->query("SELECT * FROM category ORDER BY order_numero ASC");
echo "<a href='..\pages\main_menu.php'><img id=\"label\" src=\"..\gfx/label.png\"></a>";
echo "<h1>Меню</h1>";
echo "<form name='act' action='inner_menu.php' method='post'>";
echo "<div id='container'>";
foreach ($result as $row) {
    $picture_url = "" . $row['picture_url'];
    //Если не существует картинки по указанному адресу
//    if (!file_exists($picture_url))
//        //Картинка по-умолчанию
//        $row['picture_url'] = "../gfx/table.jpg";
    echo "<button class='category' type='submit' name='category_id' value='". $row['category_id'] ."'>
                <img src='" . $row['picture_url'] . "'>
                <span class='cat_label'>" . $row['category_name'] . "</span>
                </img> 
          </button>";
}
echo "</div>";
echo "</form>";




//Боковое меню
require "right_menu.php";

?>

<!--<div class="flag">
    <section role="region" aria-hidden="true" class="italy-flag">
    </section>
</div>-->