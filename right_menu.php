<input type="checkbox" id="nav-toggle" hidden>
<nav class="nav">
    <label for="nav-toggle" class="nav-toggle" onclick></label>
    <h2 class="logo">
        <a>Категории</a>
    </h2>
    <ul>

        <?php

        echo "<form action='inner_menu.php' method='post'>";

        $result = $link->query("SELECT * FROM category ORDER BY order_numero ASC");

        foreach ($result as $row) {
            echo "<li>
<button type='submit' name='category_id' value='". $row['category_id'] ."'>".
                $row['category_name'].
                "</button>";
        }
        echo "<li><a style='margin: 100px 0px 0px' href='../admin.php'>Авторизация</a>";

        echo "</form>";
        ?>
    </ul>
</nav>