<?php 
session_start();
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Nhà sách Quốc Huy</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="View/assets/bootstrap/css/bootstrap.min.css" />
		<link rel="stylesheet" href="View/assets/css/main.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<noscript><link rel="stylesheet" href="View/assets/css/noscript.css" /></noscript>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	
</head>
	<body class="is-preload">
		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
					<div class="dropdown show" style="margin:1px;">
					<!-- dang nhap  -->
  <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  <i class="fa-solid fa-user"></i>  <?php if (isset($_SESSION['user_id'])): ?> 
      <?php echo $_SESSION['email']; ?> <!-- Display user's name -->
    <?php else: ?>
      Tài Khoản <!-- chua dang nhap -->
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
								<a href="index.php" class="logo">
								<span class="fa fa-book"></span>
								<span class="title">Nhà sách Quốc Huy</span>
								</a>
								<form action="../Model/search.php" method="get"  style="width:400px;">
								<div class="input-group my-5">
									<input type="text" class="form-control" name="key" placeholder="Tìm kiếm sách" 
										aria-label="Search Book..." aria-describedby="basic-addon2">
									<button class="input-group-text btn btn-primary" id="basic-addon2">
										<img src="View/images/search.png" width="20px">
									</button>
							</div>
							</form>
						
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
							<li><a href="index.php" class="active">Trang chủ</a></li>

							<li><a href="View/view-product.php">Sản phẩm</a></li>

							<li><a href="View/cart.php">Giỏ hàng</a></li>
							<li><a href="View/order.php"> Đơn hàng</a></li>
							<li class="nav-item dropdown">
    <a href="#" class="dropdown-toggle nav-link">Thể loại</a>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="View/about.html">Văn học</a></li>
        <li><a class="dropdown-item" href="View/test.php">Kỹ năng sống</a></li>
        <li><a class="dropdown-item" href="View/testimonials.html">Testimonials</a></li>
        <li><a class="dropdown-item" href="View/terms.html">Terms</a></li>
    </ul>
</li>

							<!-- <li >
		          <?php if (isset($_SESSION['user_id'])) {?>
		          	<a  
		             href="View/admin.php"></a>
		          <?php }else{ ?>
		          <a 
		             href="View/login.php"></a>
		          <?php } ?>

		        </li>	 -->

							<li><a href="View/contact.html">Contact Us</a></li>
						</ul>
					</nav>

	
