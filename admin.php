<?php
session_start();

$echo = null;
require 'mysql.php';

$echo = "<form action='admin.php' method='post'>
                                      <input type='text' placeholder='Логин' class='input'
                                        name='login' required><br>
                                     <input type='password' placeholder='Пароль' class='input'
                                       name='password' required><br>
                                    <input type='submit' value='Войти' class='button'>
                      </form>";

?>

<!DOCTYPE html>
<html>
<head>
    <title>Авторизация</title>
    <!--<link rel='stylesheet' href='inc/style.css'>-->
</head>
<body>
<H1>Авторизация</H1>
<div class='wrapper'>
    <main class='main' id='main'>
        <?php echo $echo; ?>
    </main>
</div>
</body>
</html>

<?php



function login($db,$login,$password) {
    require 'mysql.php';

    //Обязательно нужно провести валидацию логина и пароля, чтобы исключить вероятность инъекции

                 //Запрос в базу данных

                $loginResult = mysqli_query($db,"SELECT * FROM admin_account WHERE login='$login' AND password='$password'");

                if(mysqli_num_rows($loginResult) == 1) {  //Если есть совпадение, возвращается true

                                return true;

                } else {//Если такого пользователя не существует, данные стираются,    а возвращается false
                    echo "Неправильный пароль или логин";
                                 unset($_SESSION['login'],$_SESSION['password']);
                                  return false;
              }

}

if(isset($_POST['login']) && isset($_POST['password'])) {
    $_SESSION['login'] = $_POST['login'];
    $_SESSION['password'] = $_POST['password'];

}

if(isset($_SESSION['login']) && isset($_SESSION['password'])) {

    if(login($link,$_SESSION['login'],$_SESSION['password'])) {//Попытка авторизации
        $_SESSION['login'] = $_POST['login'];
        $_SESSION['password'] = $_POST['password'];
        //Тут будут проходить все операции
        echo "Вы вошли как " . $_SESSION['login'];

        session_write_close();
        $new_url = 'inAdmin.php';
        header('Location: '.$new_url);
}

}