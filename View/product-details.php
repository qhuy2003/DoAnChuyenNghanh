
<?php
session_start(); // Khởi động session nếu chưa có
include '../config/db_conn.php';
// Kiểm tra xem user_id có tồn tại trong session không
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = null; // Gán giá trị mặc định nếu user_id không tồn tại
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
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

	</head>
	<body class="is-preload">
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

				<!-- Main -->
<div id="main">
    <?php
    // Kiểm tra xem id sách có được truyền không
    if (isset($_GET['id'])) {
        // Truy vấn dữ liệu sách theo id
        $get_product = $conn->prepare("SELECT * FROM `books` WHERE id = ?");
        $get_product->execute([$_GET['id']]);

        // Kiểm tra nếu sách tồn tại
        if ($get_product->rowCount() > 0) {
            // Duyệt qua kết quả
            while ($fetch_get = $get_product->fetch(PDO::FETCH_ASSOC)) {
    ?>      
                <div class="inner">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- Hình ảnh sách -->
                            <div class="col-md-5">
                            <a class="link-dark d-block text-center" href="../Uploads/files/<?=$fetch_get['file']?>">

                            <img src="../Uploads/cover/<?= htmlspecialchars($fetch_get['cover']); ?>" alt="cover"> 
</a>
                            </div>
                            <!-- Thông tin sách -->
                            <div class="col-md-7">
                                <div>
                                    <h3 class="name"><?= htmlspecialchars($fetch_get['title']); ?></h3>
                                    <p class="description">
                                        <?= htmlspecialchars($fetch_get['description']); ?>
                                    </p>
                                    <h4>Giá: <?= number_format($fetch_get['price'], 0, ',', '.') . " VND"; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    <?php
                // Tính tổng giá
            }
        } else {
            echo "<p>Không tìm thấy sách.</p>";
        }
    } else {
        echo "<p>ID sách không hợp lệ.</p>";
    }
    ?>
<br>
<h2 class="h2">Các sản phẩm khác</h2>

<section class="product-other">

<?php 
  // Truy vấn lấy sách thuộc thể loại 'Thiếu nhi'
  $select_books = $conn->prepare("SELECT * FROM books ORDER BY RAND() LIMIT 3;");
  $select_books->execute();

  if ($select_books->rowCount() > 0) {
    while ($book = $select_books->fetch(PDO::FETCH_ASSOC)) {
      ?>
      <article class="style3">
        <span class="image">
          <img src="../Uploads/cover/<?= htmlspecialchars($book['cover'], ENT_QUOTES, 'UTF-8');  ?> " />
        </span>

        <!-- Liên kết đến trang chi tiết sản phẩm -->
        <a href="product-details.php?id=<?= $book['id'] ?>">
          <h2><?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
          <p><strong><?= number_format($book['price'], 0, ',', '.') ?> đ</strong></p>
        </a>
      </article>

      <?php
    }
  } 
?>

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
  
			
	</body>
</html>
<!-- Scripts --> 
<script src="View/assets/js/jquery.min.js"></script>
<script src="View/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="View/assets/js/jquery.scrolly.min.js"></script>
<script src="View/assets/js/jquery.scrollex.min.js"></script>
<script src="View/assets/js/main.js"></script>
<?php include 'components/alert.php'; ?>
