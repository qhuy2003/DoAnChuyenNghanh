<?php
session_start(); // Khởi động session nếu chưa có

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

	</head>
	<body class="is-preload">
		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<div class="inner">

						<a href="../index.php" class="logo">
								<span class="fa fa-book"></span> <span class="title">Nhà sách Quốc Huy</span>
						</a>
						
							<!-- Biểu tượng giỏ hàng -->
							<a href="" class="cart-link">
								<i class="fa-solid fa-cart-shopping"></i> Giỏ Hàng 
							</a>


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
							<li><a href="../index.php">Home</a></li>

							<li><a href="products.html" class="active">Products</a></li>

							<li><a href="checkout.html">Checkout</a></li>

							<li>
								<a href="#" class="dropdown-toggle">About</a>

								<ul>
									<li><a href="about.html">About Us</a></li>
									<li><a href="blog.html">Blog</a></li>
									<li><a href="testimonials.html">Testimonials</a></li>
									<li><a href="terms.html">Terms</a></li>
								</ul>
							</li>

							<li><a href="contact.html">Contact Us</a></li>
						</ul>
					</nav>

				<!-- Main -->
					<div id="main">
						<div class="inner">
							<h1>Đời sống bí ẩn của cây (tái bản) <span class="pull-right"><del>150,000 đ</del> 135,000 đ</span></h1>
							
							<div class="container-fluid">
								<div class="row">
									<div class="col-md-5">
										<img src="images/doi-song-bi-an-cua-cay-tb-2021.jpg" class="img-fluid" alt="">
									</div>
									
									<div class="col-md-7">
										<p> <strong>Đời Sống Bí Ẩn Của Cây<br>
											Chúng cảm thấy gì?<br>
											Chúng giao tiếp thế nào?<br>
											Những phát hiện từ Thế Giới Bí Mật</strong></p>

										<p>Được xem là một trong những quyển sách hay nhất về cây cối, 
											"Đời sống bí ẩn của cây" mở ra một thế giới kỳ diệu về đời sống xã hội phức 
											tạp của những khu rừng ôn đới. Những cái cây giao tiếp với nhau, thể hiện cá tính riêng, hỗ trợ 
											nhau lớn lên, chia sẻ chất dinh dưỡng cho những cá nhân đang chống chọi bệnh tật và thậm chí cảnh báo nhau
											 về những nguy hiểm sắp xảy ra... Không chỉ gây bất ngờ với những thông tin hấp dẫn về các loài cây cố
											 i mà lâu nay chúng ta vẫn xem là vô tri vô giác, trong tác phẩm này, Wohlleben còn chia sẻ tình yêu 
											 sâu sắc của ông đối với cây và rừng, đồng thời giải thích các tiến trình thú vị của sự sống, 
											cái chết và sự tái sinh mà ông đã quan sát được trong chính khu rừng của mình.</p>
										

							                <form action="add_to_cart.php" method="POST">
											<div class="col-sm-8">
												<label class="control-label">Số lượng</label>
												<div class="row">
													<div class="col-sm-6">
														<div class="form-group">
															<input type="number" name="quantity" id="quantity" min="1" value="1" required>
														</div>
													</div>
													<div class="col-sm-6">
														<input type="hidden" name="product_id" value="">
														<input type="submit" class="primary" name="add_to_cart" value="Add to Cart">
													</div>
												</div>
											</div>
										</form>
							            </div>
									</div>
								</div>
							</div>

							<br>
							<br>

							<div class="container-fluid">
								<h2 class="h2">Similar Products</h2>

								<!-- Products -->
								<section class="tiles">
									<article class="style1">
										<span class="image">
											<img src="images/product-2-720x480.jpg" alt="" />
										</span>
										<a href="product-details.html">
											<h2>Lorem ipsum dolor sit amet, consectetur</h2>
											
											<p><del>$19.00</del> <strong>$19.00</strong></p>

											<p>Vestibulum id est eu felis vulputate hendrerit uspendisse dapibus turpis in </p>
										</a>
									</article>

									<article class="style2">
										<span class="image">
											<img src="images/product-2-720x480.jpg" alt="" />
										</span>
										<a href="product-details.html">
											<h2>Lorem ipsum dolor sit amet, consectetur</h2>
											
											<p><del>$19.00</del> <strong>$19.00</strong></p>

											<p>Vestibulum id est eu felis vulputate hendrerit uspendisse dapibus turpis in </p>
										</a>
									</article>

									<article class="style3">
										<span class="image">
											<img src="images/product-6-720x480.jpg" alt="" />
										</span>
										<a href="product-details.html">
											<h2>Lorem ipsum dolor sit amet, consectetur</h2>
											
											<p><del>$19.00</del> <strong>$19.00</strong></p>

											<p>Vestibulum id est eu felis vulputate hendrerit uspendisse dapibus turpis in </p>
										</a>
									</article>
								</section>
							</div>
						</div>
					</div>

				<!-- Footer -->
					<footer id="footer">
						<div class="inner">
							<section>
								<ul class="icons">
									<li><a href="#" class="icon style2 fa-twitter"><span class="label">Twitter</span></a></li>
									<li><a href="#" class="icon style2 fa-facebook"><span class="label">Facebook</span></a></li>
									<li><a href="#" class="icon style2 fa-instagram"><span class="label">Instagram</span></a></li>
									<li><a href="#" class="icon style2 fa-linkedin"><span class="label">LinkedIn</span></a></li>
								</ul>

								&nbsp;
							</section>

							<ul class="copyright">
								<li>Copyright © 2020 Company Name </li>
								<li>Template by: <a href="https://www.phpjabbers.com/">PHPJabbers.com</a></li>
							</ul>
						</div>
					</footer>

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>