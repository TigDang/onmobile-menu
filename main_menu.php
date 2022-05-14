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
echo "<form name='act' action='inner_menu.php' method='post'>";
echo "<div id='container'>";
foreach ($result as $row) {
    //Если не существует картинки по указанному адресу
    if (!file_exists($row['picture_url']))
        //Картинка по-умолчанию
        $row['picture_url'] = "gfx/table.jpg";
    echo "<button type='submit' name='category_id' value='". $row['category_id'] ."'><div class='category'> 
            <br>
                <img src='" . $row['picture_url'] . "'>
                <span class='cat_label'>" . $row['category_name'] . "</span>
                </img> 
          </div>
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