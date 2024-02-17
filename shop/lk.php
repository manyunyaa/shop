<!DOCTYPE html>

<html>
<head>
    <title>Вход в личный кабинет</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php

$addr = "localhost";
$user = "root";
$pass = "";
$db = "shop"; 

session_start();

if (isset($_GET['exit'])) {
    session_unset();
    session_destroy();
}

$connect = mysqli_connect($addr,$user,$pass,$db);

$flag = isset($_POST['phone_number']);

if ($flag):
    $sql = "select client_id from shop.clients where phone LIKE '%$_POST[phone_number]%' and pass = sha1('".$_POST['pass']."')";
    $result = mysqli_query($connect, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row):
            $_SESSION['client'] = $row['client_id'];
            $flag = false;   
    else:?>
        <div class="container"><h3>Неверно введен номер телефона или пароль</h3></div>
    <?php endif;

endif; ?>

<?php if ($flag or !isset($_SESSION['client'])): ?>
    <div class="header"><a href="index.php">MANYAO</a></div>
    <div class="container">
        <form action="lk.php" method="POST" id="form">
            <h1>Вход в личный кабинет</h1>
            <hr>
            <label for="phone_number"><b>Номер телефона</b></label>
            <input type="text" placeholder="8" name="phone_number" required>
            <label for="pass"><b>Пароль</b></label>
            <input type="password" placeholder="" name="pass" required>
            <button type="submit">Войти</button>
        </form>
    </div>
<?php else: ?>
    <div class="header">
        <a href="index.php">MANYAO</a>
        <a href="cart.php">Корзина</a>
        <form action="lk.php" method="GET">
            <input type="hidden" name="exit" value="true">
            <button type="submit">Выход</button>
        </form>
    </div>
    <div class="container">
        <form action="lk.php" method="POST" id="form">
            <label for="good"><b>Поиск товара</b></label>
            <input type="text" placeholder="Я ищу..." name="good" required>
            <button type="submit">Искать</button>
        </form>
    </div>
    <?php

  
   if(isset($_POST['good'])){
    $sql = "select clothes_id, clothes_name, color, price,img  from shop.clothes inner JOIN shop.clothes_price USING(clothes_id) where date_of_end IS NULL and clothes_name LIKE '%$_POST[good]%';";
    $result1 = mysqli_query($connect, $sql);
    $row_cnt = mysqli_num_rows($result1);
    if($row_cnt < 1 ):?>
         <div class="container"><h3>Упс! Ничего не найдено по вашему запросу. Перейдите к каталогу для дальнейшего поиска</h3>
        </div><?php  
        endif;
   }
   else{
    $sql = "select clothes_id, clothes_name, color, price,img from shop.clothes inner JOIN shop.clothes_price USING(clothes_id) where date_of_end IS NULL ;";
    }
   

   $result = mysqli_query($connect, $sql);
    


     while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="container">
        <p><?php echo $row['clothes_name']." " ?><?php echo $row['color']." " ?><br><?php echo  $row['price']. " " ?><?php echo "рублей<br>" ?><center><?php echo '<img src="'.$row["img"].'"'  ?></center><br><br></p>
            <form action="description.php" method="GET">
                <input type="hidden" name="clothes_id" value="<?php echo $row['clothes_id']; ?>">
                <button type="submit">Описание товара</button>
            </form>
        </div>
    <?php endwhile;
endif;
mysqli_close($connect);
?>
</body>
</html>