<?php 
include '../config/db_conn.php';

if(isset($_COOKIE['user_id'])){
    $user_id=$_COOKIE['user_id'];//Nếu cookie user_id tồn tại, giá trị của nó được gán vào biến $user_id.
}else {
    setcookie('user_id',create_unique_id(),time(),+60*60*24*30);// thoi diem cookie het han 30 ngay
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>View Products</title>
   <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
   <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
   <link rel="stylesheet" href="assets/css/main.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" /></head>
<body>
<div id="wrapper">

<!-- Header -->
   <header id="header">
   <i class="fa-solid fa-user"></i>

   <?php 
        
   if(isset($_SESSION['email'])){
      echo"<a>Hi! ".$_SESSION['email']."</a>";
   }
   
        else 
        {
        }
   if(!isset($_SESSION['email'])){
            echo "<a href='View/login-user.php'>Đăng nhập</a>";
        }
            

        else {
      echo "<a href='View/logout-user.php'>| Logout</a>";

        }
        ?>			
            
   <div class="inner">

         <!-- Logo -->
         <div class="header">
            <a href="../index.php" class="logo">
            <span class="fa fa-book"></span>
            <span class="title">Nhà sách Quốc Huy</span>
            </a>      
         </div>		

         <!-- Nav -->
            <nav>
               <ul>
                  <li><a href="#menu">Menu</a></li>
               </ul>
               
            </nav>

      </div>
   </header>

<!-- Menu -->
   <nav id="menu">
      <h2>Menu</h2>
      <ul>
         <li><a href="../index.php" class="active">Trang chủ</a></li>

         <li><a href="View/view-product.php">Sản phẩm</a></li>
       
         <li><a href="View/checkout.html">Giỏ hàng</a></li>

         <li class="nav-item dropdown">
<a href="#" class="dropdown-toggle nav-link">Thể loại</a>
<ul class="dropdown-menu">
<li><a class="dropdown-item" href="View/about.html">Văn học</a></li>
<li><a class="dropdown-item" href="View/test.php">Kỹ năng sống</a></li>
<li><a class="dropdown-item" href="View/testimonials.html">Testimonials</a></li>
<li><a class="dropdown-item" href="View/terms.html">Terms</a></li>
</ul>
</li>
         <li >
    <?php if (isset($_SESSION['user_id'])) {?>
       <a  
       href="View/admin.php">Admin</a>
    <?php }else{ ?>
    <a 
       href="View/login.php">Login</a>
    <?php } ?>

  </li>	

         <li><a href="View/contact.html">Contact Us</a></li>
      </ul>
   </nav>
<!-- End Menu-->

<section class="orders">
        <h1 class="heading">Đơn hàng của tôi</h1>
        <div class="box-container">
            <?php
            $select_orders=$conn->prepare("SELECT * FROM `orders` WHERE user_id=? ORDER BY date DESC"); 
            $select_orders->execute([$user_id]);
            if ($select_orders->rowCount() > 0) {
                while($fetch_order=$select_orders->fetch(PDO::FETCH_ASSOC)){
                    $select_product=$conn->prepare("SELECT * FROM `books` WHERE id =? ");
                    $select_product->execute([$fetch_order['product_id']]);
                    if($select_product->rowCount()>0){
                        while($fetch_product=$select_product->fetch(PDO::FETCH_ASSOC)){
                            ?>
       <div class="box" <?php if($fetch_order['status'] == 'canceled'){echo 'style="border:.2rem solid red";';}; ?>>
      <a href="view-order.php?id=<?= $fetch_order['id']; ?>">
         <p class="date"><i class="fa fa-calendar"></i><span><?= $fetch_order['date']; ?></span></p>
         <img src="../Uploads/cover/<?= $fetch_product['cover']; ?>" class="image" alt="" width="400px">
         <h3 class="name"><?= $fetch_product['title']; ?></h3>
         <p class="price"><?= number_format($fetch_product['price'], 0, ',', '.'); ?> đ</p>
         <p class="status" style="color:<?php if($fetch_order['status'] == 'delivered'){echo 'green';}elseif($fetch_order['status'] == 'canceled'){echo 'red';}else{echo 'orange';}; ?>"><?= $fetch_order['status']; ?></p>
      </a>
   </div>
   <?php
                         }
                        }
                    }
                }
            else{
                echo '<p class="empty">không tìm thấy đơn hàng! </p>';
            }
            

            ?>
        
        </div>
</section>

</body>
</html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/script.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/jquery.min.js"></script>
			<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/main.js"></script>

<?php include 'components/alert.php'; ?>