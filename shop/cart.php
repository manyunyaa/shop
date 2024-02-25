 <!DOCTYPE html>

<html>
<head>
    <title>Вход</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>


<?php

$addr = "localhost";
$user = "root";
$pass = "";
$db = "shop"; 

session_start();

$connect = mysqli_connect($addr,$user,$pass,$db);

if ( isset($_SESSION['client'])): 
?>
    <div class="header">
    <a href="index.php">MANYAO</a>
    <form action="lk.php" method="GET" id="form">
        <button type="submit">Каталог товаров</button>
    </form>
</div>
    <?php

      if(isset($_GET['toClear'])):
        $sql = "delete from order_clothes where client = ". $_SESSION['client'];
        $result = mysqli_query($connect, $sql);
        if (!$result):
            http_response_code(503); ?>
            <div class="container"><h1>Ошибка! Не удалось очистить корзину</h1></div>
        <?php endif;
    endif;

$sql = "select clothes.clothes_id, clothes_name, color, price, name, amount, img  from shop.clothes inner JOIN shop.clothes_price USING(clothes_id) inner join country_manufacturer USING(country_manufacturer_id) inner join order_clothes on order_clothes.clothes_id = clothes.clothes_id " .
"where client = " .$_SESSION['client'];

   $result = mysqli_query($connect, $sql);

   $row_cnt = mysqli_num_rows($result);
   if($row_cnt < 1){ 
    http_response_code(200);?>
    <div class="container"><h1>Ваша корзина пуста. Перейдите в каталог для выбора товаров</h1></div>
<?php 
   }
else{
    while ($row = mysqli_fetch_assoc($result)):
        http_response_code(200); ?>
        <div class="container">
        <p><?php echo $row['clothes_name']." " ?><?php echo $row['color']." " ?><br><?php echo  $row['price']. " " ?><?php echo "рублей<br>" ?><?php echo  "Количество: " ?><?php echo  $row['amount']. " " ?><br><center><?php echo '<img src="'.$row["img"].'"'  ?></center><br><br></p>
            <form action="description.php" method="GET">
                <input type="hidden" name="clothes_id" value="<?php echo $row['clothes_id']; ?>">
                <button type="submit">Описание товара</button>
            </form>
        </div>
        
    <?php endwhile; ?>

<div class="container">
<form action="cart.php" method="GET">
    <input type="hidden" name="toClear" value="<?php echo $_SESSION['client']; ?>">
    <button type="submit">Очистить корзину</button>
</form>
</div>
<?php
} 
endif;
mysqli_close($connect);
?>
</body>
</html>