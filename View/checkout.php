
<?php
session_start();
include '../config/db_conn.php';

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}

if(isset($_POST['place_order'])){

   $name = $_POST['name'];
   $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
   $number = $_POST['number'];
   $number = htmlspecialchars($number, ENT_QUOTES, 'UTF-8');
   $email = $_POST['email'];
   $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
   $address =$_POST['address'];
   $address = htmlspecialchars($address, ENT_QUOTES, 'UTF-8');
   $address_type = $_POST['address_type'];
   $address_type = htmlspecialchars($address_type, ENT_QUOTES, 'UTF-8');
   $method = $_POST['method'];
   $method = htmlspecialchars($method, ENT_QUOTES, 'UTF-8');

   $verify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $verify_cart->execute([$user_id]);
   
   if(isset($_GET['id'])){

      $get_product = $conn->prepare("SELECT * FROM `books` WHERE id = ? LIMIT 1");
      $get_product->execute([$_GET['id']]);
      if($get_product->rowCount() > 0){ // mua ngay
         while($fetch_p = $get_product->fetch(PDO::FETCH_ASSOC)){
            $insert_order = $conn->prepare("INSERT INTO `orders`(id, user_id, name, number, email, address, address_type, method, product_id, price, qty) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
            $insert_order->execute([create_unique_id(), $user_id, $name, $number, $email, $address, $address_type, $method, $fetch_p['id'], $fetch_p['price'], 1]);
            header('location:order.php');
         }
      }else{
         $warning_msg[] = 'Xảy ra lỗi!';
      }

   }elseif($verify_cart->rowCount() > 0){ // gio hang

      while($f_cart = $verify_cart->fetch(PDO::FETCH_ASSOC)){

         $insert_order = $conn->prepare("INSERT INTO `orders`(id, user_id, name, number, email, address, address_type, method, product_id, price, qty) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
         $insert_order->execute([create_unique_id(), $user_id, $name, $number, $email, $address, $address_type, $method, $f_cart['product_id'], $f_cart['price'], $f_cart['qty']]);

      }

      if($insert_order){
         $delete_cart_id = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $delete_cart_id->execute([$user_id]);
         header('location:order.php');
      }

   }else{
      $warning_msg[] = 'Giỏ hàng trống!';
   }

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
   <div class="dropdown show" style="margin:1px;">
        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa-solid fa-user"></i> 
         <?php if (isset($_SESSION['user_id'])) : ?>
         <?php echo $_SESSION['email']; ?>
         <?php else: ?>
              Tài khoản
         <?php endif; ?>
        </a>
      
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
          <a class="dropdown-item" href="View/login-user.php">Đăng nhập</a>
          <a class="dropdown-item" href="View/registration.php">Đăng ký</a>
         <?php if (isset($_SESSION['user_id'])): ?> <!-- neu dang nhap se hien -->
            <a class="dropdown-item" href="../Controller/logout-user.php">Đăng xuất</a>
          <?php endif; ?>
        </div>
      </div>	
            
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

<section class="checkout">

   <h1 class="heading">Thanh Toán</h1>

   <div class="row">

   <form action="" method="POST">
      <div class="h3-info">
      <h3>Chi tiết hóa đơn</h3>
    </div>
         <div class="flex">
            <div class="box">
               <p>Họ và tên <span>*</span></p>
               <input type="text" name="name" required maxlength="50" placeholder="nhập họ và tên" class="input">
               <p>Số điện thoại <span>*</span></p>
               <input type="text" name="number" required maxlength="10" placeholder="Nhập số điện thoại" class="input" min="0" max="9999999999">
               <p>Email <span>*</span></p>
               <input type="email" name="email" required maxlength="50" placeholder="Nhập email" class="input">
               <p>Loại địa chỉ <span>*</span></p>
               <select name="address_type" class="input" required> 
                  <option value="home">Nhà </option>
                  <option value="office">Công ty</option>
               </select>
               <p>Địa chỉ <span>*</span></p>
               <input type="text" name="address" require  maxlength="50" placeholder="Nhập địa chỉ" > 
               <p>Phương thức thanh toán <span>*</span></p>
        <form action="#">          <!-- gui  den trang hien tai -->
        <input type="radio" name="method" id="cod" value="cod">
            <input type="radio" name="method" id="shopeepay" value="shopeepay">
            <input type="radio" name="method" id="zalopay" value="zalopay">
            <input type="radio" name="method" id="ATM" value="ATM">


            <div class="category-payment"  required>
            <label for="cod" class="codMethod">
                    <div class="imgName">
                        <div class="imgContainer cod">
                            <img src="images/car-deli-logo.jpg" alt="">
                        </div>
                        <span class="name">Giao Hàng Tận Nơi</span>
                    </div>
                    <span class="check"><i class="fa-solid fa-circle-check" style="color: #6064b6;"></i></span>
                </label>

                <label for="shopeepay" class="shopeepayMethod">
                    <div class="imgName">
                        <div class="imgContainer shopeepay">
                            <img src="images/shopeepay.png"  border-radius:40px alt="">
                        </div>
                        <span class="name">ShopeePay</span>
                    </div>
                    <span class="check"><i class="fa-solid fa-circle-check" style="color: #6064b6;"></i></span>
                </label>

                <label for="zalopay" class="zalopayMethod">
                    <div class="imgName">
                        <div class="imgContainer zalopay">
                            <img src="images/zalopay.png" alt="">
                        </div>
                        <span class="name">ZaloPay</span>
                    </div>
                    <span class="check"><i class="fa-solid fa-circle-check" style="color: #6064b6;"></i></span>
                </label>

                <label for="ATM" class="ATMMethod">
                    <div class="imgName">
                        <div class="imgContainer ATM">
                            <img src="images/atm.jpg" alt="">
                        </div>
                        <span class="name">ATM|Internet Banking</span>
                    </div>
                    <span class="check"><i class="fa-solid fa-circle-check" style="color: #6064b6;"></i></span>
                </label>
            </div>
        </form>
            
        
         </div>
      </form>

      <div class="summary">
      <h3 class="title">Giỏ hàng</h3>
      <?php
            $grand_total = 0;
            if(isset($_GET['id'])){
               $select_get = $conn->prepare("SELECT * FROM `books` WHERE id = ?");
               $select_get->execute([$_GET['id']]);
               while($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)){
         ?>
         <!-- //mua ngay -->
         <div class="flex">
         <img src="../Uploads/cover/<?= $fetch_get['cover']; ?>" class="image" alt="">
         <div>
               <h3 class="name"><?= $fetch_get['title']; ?></h3>
               <p class="price"> <?= $fetch_get['price']; ?> đ x 1</p>
               <?php 
               $sub_total =  $fetch_get['price'];
               $grand_total += $sub_total; 
                  ?>


            </div>
         </div>
         <?php
         //gio hang
               }
            }else{
               $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
               $select_cart->execute([$user_id]);
               if($select_cart->rowCount() > 0){
                  while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                     $select_products = $conn->prepare("SELECT * FROM `books` WHERE id = ?");
                     $select_products->execute([$fetch_cart['product_id']]);
                     $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
                     $sub_total = ($fetch_cart['qty'] * $fetch_product['price']);

                     $grand_total += $sub_total;
            
         ?>
         <div class="flex">
            
         <img src="../Uploads/cover/<?= $fetch_product['cover']; ?>" class="image" alt="">
         <div class="checkout-cart">
               <h3 class="name"><?= $fetch_product['title']; ?></h3>
               <p class="price"> <?= number_format($fetch_product['price'], 0, ',', '.'); ?> đ x <?= $fetch_cart['qty'] ; ?> đ</p>
            </div>
         </div>
         <?php
                  }
               }else{
                  echo '<p class="empty">Giỏ hàng trống </p>';
               }
            }
         ?>
         <div class="grand-total"><span><strong></strong>Tổng cộng :</span><p> <?=number_format( $grand_total, 0, ',', '.'); ?> đ</p></div>
         <div class="placeorder">
            <input type="submit" value="Đặt Hàng" name="place_order" class="btn">
            
         </div>
      </div>
      

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