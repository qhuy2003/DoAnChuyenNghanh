
<?php
session_start(); // Khởi động session nếu chưa có
include '../config/db_conn.php';
// Kiểm tra xem user_id có tồn tại trong session không
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = null; // Gán giá trị mặc định nếu user_id không tồn tại
}
$order = isset($_GET['order']) && in_array($_GET['order'], ['ASC', 'DESC']) ? $_GET['order'] : 'ASC'; // Mặc định ASC nếu không hợp lệ
$select_products = $conn->prepare("SELECT * FROM `books` ORDER BY `price` $order");
$select_products->execute();
//them vao gio hang
if(isset($_POST['add_to_cart'])){

   $id = create_unique_id();
   $product_id = $_POST['product_id'];
   $product_id = htmlspecialchars($product_id, ENT_QUOTES, 'UTF-8');
   $qty = $_POST['qty'];
   $qty = htmlspecialchars($qty, ENT_QUOTES, 'UTF-8');
   
   $verify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");   
   $verify_cart->execute([$user_id, $product_id]);

   $max_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $max_cart_items->execute([$user_id]);

   if($verify_cart->rowCount() > 0){
      $warning_msg[] = 'Đã có trong giỏ hàng!';
   }elseif($max_cart_items->rowCount() == 10){
      $warning_msg[] = 'Giỏ hàng đầy!';
   }else{
      $select_price = $conn->prepare("SELECT * FROM `books` WHERE id = ? LIMIT 1");/* truy van */
      $select_price->execute([$product_id]);/* truy van */
      $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

      $insert_cart = $conn->prepare("INSERT INTO `cart`(id, user_id, product_id, price, qty) VALUES(?,?,?,?,?)");/*them du lieu vao table cart */
      $insert_cart->execute([$id, $user_id, $product_id, $fetch_price['price'], $qty]);/*chay truy van them sp vao gio*/
      $success_msg[] = 'Đã thêm vào giỏ hàng!';
   }
}
?>
<!DOCTYPE HTML>
<html lang="en">
<html>
	<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sản phẩm</title>
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
   <link rel="stylesheet" href="assets/css/main.css">

	</head>
	<body>
		<!-- Wrapper -->
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

      
   </header>

<!-- Menu -->
   <nav id="menu">
      <h2>Menu</h2>
      <ul>
         <li><a href="../index.php" class="active">Trang chủ</a></li>

         <li><a href="view-product.php">Sản phẩm</a></li>
       
         <li><a href="checkout.php">Giỏ hàng</a></li>

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
   <div id="main">
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
<ol class="carousel-indicators">
<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
</ol>
<div class="carousel-inner">
<div class="carousel-item active">
<img class="d-block w-100" src="images/slider_2.jpg" alt="First slide">
</div>
<div class="carousel-item">
<img class="d-block w-100" src="images/Screenshot 2024-11-07 150132.png" alt="Second slide">
</div>
<div class="carousel-item">
<img class="d-block w-100" src="images/slider_3.jpg" alt="Third slide">
</div>
</div>
<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
<span class="carousel-control-prev-icon" aria-hidden="true"></span>
<span class="sr-only">Previous</span>
</a>
<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
<span class="carousel-control-next-icon" aria-hidden="true"></span>
<span class="sr-only">Next</span>
</a>
</div>
<br>
 <img class="ty-pict  ty-banner__image   cm-image" alt="" title=""  width="288" height="187"  src="images/mini1.jpg">
<img class="ty-pict  ty-banner__image   cm-image" alt="" title=""  width="288" height="187"  src="images/mini2.jpg">
<img class="ty-pict  ty-banner__image   cm-image" alt="" title=""  width="288" height="187"  src="images/mini3.jpg">
<img class="ty-pict  ty-banner__image   cm-image" alt="" title=""  width="288" height="187"  src="images/mini4.png">

				<!-- Main -->
<div class="categories-aside">
<div style="display: flex;">
   <!-- ASIDE DANH MỤC -->
   <aside class="categories" style="width: 15%; padding: 20px; border-radius: 10px;">
      <h2>Danh mục</h2>
      <h3>Thể loại</h3>
      <ul style="list-style: none; padding: 0;">
       <?php 
       $select_categories =$conn->prepare("SELECT c.id,c.name FROM categories as c");
       $select_categories->execute();
       if($select_categories->rowCount()>0){
         while($categories=$select_categories->fetch(PDO::FETCH_ASSOC)){?>
            <ul style="list-style: none; padding: 0;">
                  <li><a href="category.php?id=<?=$categories['id'] ?>&name=<?=$categories['name'] ?>">
                     <h6><?= $categories['name']; ?></h6></a>
               </li></ul>
         <?php }
       }
       ?>
         <h2>Nhà xuất bản</h2>
         <?php 
            $select_publisher =$conn->prepare(" SELECT DISTINCT publisher FROM `books` ;");
            $select_publisher->execute();
            if($select_publisher->rowCount()>0){
                  while($publisher=$select_publisher->fetch(PDO::FETCH_ASSOC)){?>
                        <ul style="list-style: none; padding: 0;">
                  <li><a href="publisher.php?publisher=<?=trim($publisher['publisher']); ?>">
                  <h6><?= $publisher['publisher']; ?></h6></a>
               </li></ul>
         <?php }

            }
      ?>
     
   </aside>  


   <!-- danh sach san pham -->
   <section class="products" style="width: 85%;">
    
    <h1 style="padding-left:20px"><?=$_GET['publisher'];?></h1>

<div class="box-container">

    <?php
    // Kiểm tra xem id sách có được truyền không
    if (isset($_GET['publisher'])) {?>
      
      
      <?php
        // Truy vấn dữ liệu nha xuat ban
        $name_publisher=trim($_GET['publisher']);//xoa khoang trang
        $get_product = $conn->prepare("SELECT * FROM `books` WHERE publisher = ?");
        $get_product->execute([$name_publisher]);
        
        // Kiểm tra nếu  tồn tại
       
?>      

         <?php 
            if($get_product->rowCount() > 0){
               while($fetch_get= $get_product->fetch(PDO::FETCH_ASSOC)){
         ?>
         <form action="" method="POST" class="box">
         <a href="product-details.php?id=<?= $fetch_get['id'] ?>">
            <img src="../Uploads/cover/<?= $fetch_get['cover']; ?>" class="image" alt="">
            
               <h3 class="name"><?= htmlspecialchars($fetch_get['title'], ENT_QUOTES, 'UTF-8'); ?></h3></a>
            <input type="hidden" name="product_id" value="<?= $fetch_get['id']; ?>">
            <div class="flex">
               <p class="price"><?= number_format($fetch_get['price'], 0, ',', '.'); ?> đ</p>
               <h3>Số lượng</h3>
               <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty">
            </div>
            <button type="submit" name="add_to_cart" >
               <i class="fa-solid fa-cart-shopping"></i> Thêm Vào Giỏ Hàng
            </button>
            <a href="checkout.php?id=<?= $fetch_get['id']; ?>" class="delete-btn">Mua Ngay</a>
         </form>
         <?php
               }
            } else {
               echo '<p class="empty">Không tìm thấy sản phẩm!</p>';
            }
          }
         ?>
      </div>
   </section>
</div>
</div>			
</body>
</html>

<!-- Footer -->
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

		
			<
  
			
<!-- Scripts --> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/script.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery.scrolly.min.js"></script>
<script src="assets/js/jquery.scrollex.min.js"></script>
<script src="assets/js/main.js"></script>
<?php include 'components/alert.php'; ?>
