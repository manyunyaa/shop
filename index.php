<!DOCTYPE html>

<html>
<head>
    <title>Войти или создать профиль</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">
    <h2 style="color:Firebrick">Модный интернет-магазин одежды MANYAO</h2>
    <h2 style="color:Firebrick">Всегда на связи!<br> Тел.: +7-(919)-000-56-78</h2>
    <h2 style="color:Firebrick">Мы в соцсетях: <br> inst: MANYAO_SHOP</h2>
</div>

<?php

$addr = "localhost";
$user = "root";
$pass = "";
$db = "shop";  

$flag = isset($_POST['phone_number']);

if ($flag):
    $pattern = '~^(8)\d{10}$~';
    if(!preg_match( $pattern,$_POST['phone_number'] ) )
    {
        $flag = false;
        ?>
        <div class="container"><h3>Неверный формат телефонного номера! Номер должен начинаться с 8 и вводиться без скобок,тире, пробелов и пр.знаков</h3></div>
    <?php
    } else{
    if ($_POST['pass'] == $_POST['repeatpass']):
        $connect = mysqli_connect($addr,$user,$pass,$db);
        $check = "select * from shop.clients where phone LIKE '%$_POST[phone_number]%';";
        $result1 = mysqli_query($connect, $check);
        $row_cnt = mysqli_num_rows($result1);
        if ($row_cnt > 0){

            $counter = false;   
            mysqli_free_result($result1);
            ?> <div class="container"><h3>Данный номер уже привязан к существующему аккаунту</h3>
            </div><?php
            
        }else{
            mysqli_free_result($result1);
            $sql = "insert into clients (phone, pass,email) values ('".$_POST['phone_number']."', sha1('".$_POST['pass']."'),(select concat(
                substring(uid, 25,2)
        , ':',  substring(uid, 27,2)
        , ':',  substring(uid, 29,2)
        , ':',  substring(uid, 31,2)
        , ':',  substring(uid, 33,2)
        , ':',  substring(uid, 35,2)
        )     AS uuid_to_mac
from    (select uuid() uid) AS alias))";
            $result = mysqli_query($connect, $sql);
            if($result): ?> <div class="container">
                <p>Регистрация успешно завершена</p>
                <p> <a href="lk.php">Войти</a>.</p>
            </div> <?php
            endif;
        }
       
        mysqli_close($connect);
    else: $flag = false; ?>
        <div class="container"><h3>Введенные пароли не совпадают. Попробуйте еще раз</h3></div>
    <?php endif;
    }
endif;

if (!$flag): ?>
    <div class="container">
        <form action="" method="POST">
        <h1>Создать профиль</h1>
            <hr>
            <label for="phone_number"><b>Номер телефона</b></label>
            <input type="text" placeholder="8" name="phone_number" required>
            <label for="pass"><b>Пароль</b></label>
            <input type="password" placeholder="" name="pass" required>
            <label for="repeatpass"><b>Повторите пароль</b></label>
            <input type="password" placeholder="" name="repeatpass" required>
            <hr>
            <button type="submit">Зарегистрироватся</button>
        </form>
    </div>
    <div class="container"><p>Уже есть аккаунт? <a href="lk.php">Войти</a>.</p></div>
<?php endif; ?>
</body>
</html>