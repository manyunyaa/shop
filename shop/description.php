<!DOCTYPE html>

<html>
<head>
    <title>Описание товара</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php

$addr = "localhost";
$user = "root";
$pass = "";
$db = "shop"; 

session_start();

if (isset($_REQUEST['clothes_id']) and isset($_SESSION['client'])): ?>
    <div class="header">
        <a href="index.php">MANYAO</a>
        <a href="cart.php">Корзина</a>
        <form action="lk.php" method="GET" id="form">
            <button type="submit">Каталог товаров</button>
        </form>
    </div>

    <?php
     $connect = mysqli_connect($addr,$user,$pass,$db);

    $sql = "select clothes_name, color, price, name, img  from shop.clothes inner JOIN shop.clothes_price USING(clothes_id) inner join country_manufacturer USING(country_manufacturer_id) where clothes_id = " .
    $_REQUEST['clothes_id'];
    $result = mysqli_query($connect, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row):
        http_response_code(200);?>
            <div class="container"><p><center><?php echo $row['clothes_name']; ?></p></center></p></div>
            <div class="container">
                <p><?php echo "Цвет:  " ?><?php echo $row['color']; ?></p>
                <p><?php echo "Цена:  " ?><?php echo $row['price'] ?><?php echo "  рублей" ?></p>
                <p><?php echo "Страна производитель:  " ?><?php echo $row['name']; ?></p>
                <p><center><?php echo '<img src="'.$row["img"].'"'  ?></center></p>
            </div>
            <div class="container">
                <form action="description.php" method="POST">
                    <input type="hidden" name="clothes_id" value="<?php echo $_REQUEST['clothes_id']; ?>">
                    <label for="amount"><b><center>Добавить в корзину</center></b></label>
                    <input type="text" placeholder="В количестве..." name="amount" required>
                    <button type="submit">Добавить</button>
                </form> 
                </div>

            <?php
           else: 
            http_response_code(404); ?>
            <div class="container"><h1>Данный товар был удален</h1></div>
        <?php endif;
    else:
        http_response_code(404); ?>
        <div class="container"><h1>404 Not Found</h1></div>
    <?php endif;

if (isset($_POST['amount'])):
    $sql = "insert into order_clothes(clothes_id, client, amount, date) values (" . $_POST['clothes_id'] . ", " .
        $_SESSION['client'] . ", '" . $_POST['amount'] . "',NOW())";
    $result = mysqli_query($connect, $sql);
    http_response_code(201);?>
    <div class="container"><h1>Товар был добавлен в корзину</h1></div>
<?php
    if (!$result):
        http_response_code(503); ?>
        <div class="container"><h1>Ошибка! Товар не был добавлен в корзину</h1></div>
    <?php endif;
endif;

    mysqli_close($connect);
 ?>
<?php ?>
</body>
</html>