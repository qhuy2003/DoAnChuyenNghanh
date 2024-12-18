<?php
session_start();
include '../config/db_conn.php';

if(isset($_SESSION['user_id'])){
   $user_id =$_SESSION['user_id'];
}

if(isset($_POST['update_cart'])){

   $cart_id = $_POST['cart_id'];
   $cart_id = htmlspecialchars($cart_id, ENT_QUOTES, 'UTF-8');
   $qty = $_POST['qty'];
   $qty = htmlspecialchars($qty, ENT_QUOTES, 'UTF-8');

   $update_qty = $conn->prepare("UPDATE `cart` SET qty = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);

   $success_msg[] = 'Cập nhật thành công!';

}

if(isset($_POST['delete_item'])){

   $cart_id = $_POST['cart_id'];
   $cart_id = htmlspecialchars($cart_id, ENT_QUOTES, 'UTF-8');

   $verify_delete_item = $conn->prepare("SELECT * FROM `cart` WHERE id = ?");
   $verify_delete_item->execute([$cart_id]);

   if($verify_delete_item->rowCount() > 0){
      $delete_cart_id = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
      $delete_cart_id->execute([$cart_id]);
      $success_msg[] = 'Giỏ hàng đã xóa!';
   }else{
      $warning_msg[] = 'Giỏ hàng đã được xóa!';
   } 

}

if(isset($_POST['empty_cart'])){
   
   $verify_empty_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $verify_empty_cart->execute([$user_id]);

   if($verify_empty_cart->rowCount() > 0){
      $delete_cart_id = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart_id->execute([$user_id]);
      $success_msg[] = 'Cart emptied!';
   }else{
      $warning_msg[] = 'Cart already emptied!';
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
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
   <noscript><link rel="stylesheet" href="View/assets/css/noscript.css" /></noscript>

   <link rel="stylesheet" href="assets/css/main.css">

</head>
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
            <?php 
            
            $count_cart_items=$conn->prepare("SELECT * FROM cart WHERE user_id=?");
            $count_cart_items->execute([$user_id]);// truyen tham so , la id  người dùng cần kiểm tra giỏ hàng
            $total_cart_items= $count_cart_items->rowCount();// lay so luong
         ?>
            <a href="cart.php"> <i class="fa-solid fa-cart-shopping"></i>Giỏ Hàng  <?= $total_cart_items;?></a>
          
							
            
      
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

         <li><a href="view-product.php">Sản phẩm</a></li>
       
         <li><a href="checkout.html">Giỏ hàng</a></li>

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

<h1 class="heading">Giỏ Hàng</h1>
   <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);
      if($select_cart->rowCount() > 0){
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){

         $select_products = $conn->prepare("SELECT * FROM `books` WHERE id = ?");
         $select_products->execute([$fetch_cart['product_id']]);
         if($select_products->rowCount() > 0){
            $fetch_product = $select_products->fetch(PDO::FETCH_ASSOC);
   ?>
   <form action="" method="POST" class="box-cart">
      <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
      <img src="../Uploads/cover/<?= $fetch_product['cover']; ?>" class="image" alt="" width="200px">
      <h3 class="name"><?= $fetch_product['title'] ?></h3>
      <div class="flex">
         <p class="price"> <?= number_format($fetch_cart['price'],0,',','.'); ?> đ</p>
         <input type="number" name="qty" required min="1" value="<?= $fetch_cart['qty']; ?>" max="99" maxlength="2" class="qty">
         <button type="submit" name="update_cart" class="fas fa-edit">
         </button>
      </div>
      <p class="sub-total"><strong>Tổng tiền : <span> <?= $sub_total = ($fetch_cart['qty'] * $fetch_cart['price']); ?> đ</strong></span></p>
      <input type="submit" value="Xóa" name="delete_item" class="delete-btn" onclick="return confirm('Xóa vật phẩm này ?');">
   </form>
   <?php
      $grand_total += $sub_total;
      }else{
         echo '<p class="empty">product was not found!</p>';
      }
      }
   }else{
      echo '<p class="empty">Giỏ hàng trống!</p>';
   }
   ?>
   </div>

   <?php if($grand_total != 0){ ?>
      <div class="cart-total">
         <p><strong>Tổng Cộng :</strong> <span> <?= $grand_total; ?> đ</span></p>
         <form action="" method="POST">
         <button type="submit" name="empty_cart" class="btn">  Xóa Giỏ Hàng </button>  
      </form>
         <a href="checkout.php" class="btn-checkout">Thanh Toán</a>
      </div>
   <?php } ?>

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