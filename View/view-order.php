<?php
session_start();
include '../config/db_conn.php';

if(isset($_SESSION['user_id'])){
	$user_id = $_SESSION['user_id'];
 }
 
 if(isset($_GET['id'])){
	$get_id = $_GET['id'];
 }else{
	$get_id = '';
	header('location:order.php');
 }
 
 if(isset($_POST['cancel'])){
 
	$update_orders = $conn->prepare("UPDATE `orders` SET status = ? WHERE id = ?");
	$update_orders->execute(['đã hủy', $get_id]);
	header('location:order.php');
 
 }if(isset($_POST['delivered'])){
 
	$update_orders = $conn->prepare("UPDATE `orders` SET status = ? WHERE id = ?");
	$update_orders->execute(['Đã nhận hàng', $get_id]);
	header('location:order.php');
 
 }
 
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Nhà sách Quốc Huy</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	</head>
	<body class="is-preload">
		<!-- Wrapper -->
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
                        echo "<a href='login-user.php'>Đăng nhập</a>";
                    }
                        
            
                    else {
						echo "<a href='logout-user.php'>| Logout</a>";

                    }
                    ?>			
								
					<div class="inner">

							<!-- Logo -->
							<div class="header">
								<a href="index.php" class="logo">
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
							<li><a href="view-product.php">Sản phẩm</a></li>
							<li><a href="view-product.php">Giỏ hàng</a></li>
							<li><a href="order.php"> Đơn hàng</a></li>
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
		             href="admin.php">Admin</a>
		          <?php }else{ ?>
		          <a 
		             href="login.php">Admin</a>
		          <?php } ?>

		        </li>	
							<li><a href="contact.html">Contact Us</a></li>
						</ul>
					</nav>


<section class="order-details2">

<h1 class="heading">Chi tiết đơn hàng</h1>

<div class="box-container">

<?php
   $grand_total = 0;
   $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE id = ? LIMIT 1");
   $select_orders->execute([$get_id]);
   if($select_orders->rowCount() > 0){
	  while($fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC)){
		 $select_product = $conn->prepare("SELECT * FROM `books` WHERE id = ? LIMIT 1");
		 $select_product->execute([$fetch_order['product_id']]);
		 if($select_product->rowCount() > 0){
			while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){
			   $sub_total = ($fetch_order['price'] * $fetch_order['qty']);
			   $grand_total += $sub_total;
?>
<div class="box">
   <div class="col">
	  <p class="title"><i class="fas fa-calendar"></i><?= $fetch_order['date']; ?></p>
	  <img src="../Uploads/cover/<?= $fetch_product['cover']; ?>" class="image" alt="" width="400px">
	  <p class="price"><?= number_format($fetch_product['price'], 0, ',', '.'); ?> đ</p>
	  <h3 class="name"><?= $fetch_product['title']; ?></h3>
	  <p class="grand-total">grand total : <span> <?= $grand_total; ?></span></p>
   </div>
   <div class="col">
	  <p class="title">billing address</p>
	  <p class="user"><i class="fas fa-user"></i> <?= $fetch_order['name']; ?></p>
	  <p class="user"><i class="fas fa-phone"></i> <?= $fetch_order['number']; ?></p>
	  <p class="user"><i class="fas fa-envelope"></i> <?= $fetch_order['email']; ?></p>
	  <p class="user"><i class="fas fa-map-marker-alt"></i> <?= $fetch_order['address']; ?></p>
	  <p class="title">Trạng thái</p>
	  <p class="status" style="color:<?php if($fetch_order['status'] == 'delivered'){echo 'green';}elseif($fetch_order['status'] == 'canceled'){echo 'red';}else{echo 'orange';}; ?>"><?= $fetch_order['status']; ?></p>
	  <?php if($fetch_order['status'] == 'đã hủy'){ ?>
		 <button><a href="checkout.php?id=<?= $fetch_product['id']; ?>" class="btn">Đặt lại</a></button>
	  <?php }else{ ?>
	  <form action="" method="POST">
	  <input type="submit" value="Đã nhận hàng" name="delivered" class="delete-btn" onclick="return confirm('Xác nhận đã nhận ');">
		 <input type="submit" value="Hủy đơn hàng" name="cancel" class="delete-btn" onclick="return confirm('Hủy đơn hàng này ?');">
	  </form>
	  <?php } ?>
   </div>
</div>
<?php
		 }
	  }else{
		 echo '<p class="empty">product not found!</p>';
	  }
   }
}else{
   echo '<p class="empty">no orders found!</p>';
}
?>

</div>
</section>

                    	<!-- Footer -->
    <footer id="footer">
						<div class="inner">
							<section>
								<h2>Liên hệ chúng tôi</h2>
								<form method="post" action="#">
									<div class="fields">
										<div class="field half">
											<input type="text" name="name" id="name" placeholder="Tên" />
										</div>

										<div class="field half">
											<input type="text" name="email" id="email" placeholder="Email" />
										</div>

										<div class="field">
											<input type="text" name="subject" id="subject" placeholder="Chủ đề" />
										</div>

										<div class="field">
											<textarea name="message" id="message" rows="3" placeholder="Ghi chú"></textarea>
										</div>

										<div class="field text-right">
											<label>&nbsp;</label>

											<ul class="actions">
												<li><input type="submit" value="Gửi tin nhắn" class="primary" /></li>
											</ul>
										</div>
									</div>
								</form>
							</section>
							<section>
								<h2>Thông tin liên hệ</h2>

								<ul class="alt">
									<li><span class="fa fa-envelope-o"></span> <a href="#">nhasachquochuy@gmail.com</a></li>
									<li><span class="fa fa-phone" ></span> 0933705051 </li>
									<li><span class="fa fa-map-pin"></span> 180 Đ. Cao Lỗ Phường 4, Quận 8, Hồ Chí Minh</li>
								</ul>


								<ul class="icons">
									<li><a href="#" class="icon style2 fa-facebook"><span class="label">Facebook</span></a></li>
									<li><a href="#" class="icon style2 fa-instagram"><span class="label">Instagram</span></a></li>
									<li><a href="#" class="icon style2 fa-twitter"><span class="label">Instagram</span></a></li>

								</ul>
							</section>

							<ul class="copyright">
								<li>Copyright © 2024 Nhà sách Quốc Huy </li>
							</ul>
						</div>
					</footer>

		
			<div class="zalo-icon">
    <a href="https://zalo.me/g/crgmzz518" target="_blank">
      <img src="images/zalo (1).png" alt="Zalo" width="160px">
    </a>
  </div>
  <div class="phone-icon">
    <a href="tel:0902000341">
      <i class="fa-solid fa-phone"></i>
    </a>
  </div>
  <button
        type="button"
        class="btn btn-primary"
        id="btn-back-to-top">
  <i class="fas fa-arrow-up"></i>
</button>
  
		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/main.js"></script>

			 	<?php include 'components/alert.php';?>
	</body>
</html>