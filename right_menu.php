<input type="checkbox" id="nav-toggle" hidden>
<nav class="nav">
    <label for="nav-toggle" class="nav-toggle" onclick></label>
    <h2 class="logo">
        <a href="">Категории</a>
    </h2>
    <ul>

        <?php

        $result = $link->query("SELECT * FROM category ORDER BY order_numero ASC");

        foreach ($result as $row) {
            echo "<li><a style=\"cursor: pointer;\" onclick=\"window.location='../inner_menu.php/?category_id=" . $row['category_id'] . "';\">". $row['category_name']."";
        }
        echo "<li><a style='margin: 100px 0px 0px' href='admin.php'>Авторизация</a>";
        ?>
    </ul>
</nav>