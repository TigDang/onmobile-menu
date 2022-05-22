<?php
session_start();
if (isset($_SESSION["login"])) {
    $author = $_SESSION['login'];
    echo "<br>Вы успешно вошли как " . $author;
} else {
    $new_url = 'admin.php';
    header('Location: ' . $new_url);
}

$sql = 'SELECT admin_privilege_num FROM admin_account WHERE login="' . $author . '"';
$admin_priv_res_query = $link->query($sql)->fetch_all(MYSQLI_ASSOC);