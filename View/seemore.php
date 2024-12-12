<?php  
session_start();
include "db_conn.php";

# Book helper function
include "php/func-book.php";

# author helper function
include "php/func-author.php";
$authors = get_all_author($conn);

# Category helper function
include "php/func-category.php";
$sortOrder = isset($_GET['kytu']) && $_GET['kytu'] === 'desc' ? 'DESC' : 'ASC';
// Lấy dữ liệu sách theo thứ tự sắp xếp
$books = get_all_books($conn, $sortOrder);


?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Nhà sách Quốc Huy</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/css/main.css" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <!-- bootstrap 5 Js bundle CDN-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>

		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
	</head>
	<body class="is-preload">
		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<div class="inner">

							<!-- Logo -->
								<a href="../index.php" class="logo">
									<span class="fa fa-book"></span> <span class="title">Nhà sách Quốc Huy</span>
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
                    <form action="search.php"
             method="get" 
             style="width: 100%; max-width: 30rem">

       	<div class="input-group my-5">
		  <input type="text" 
		         class="form-control"
		         name="key" 
		         placeholder="Tìm kiếm sách" 
		         aria-label="Search Book..." 
		         aria-describedby="basic-addon2">

		  <button class="input-group-text
		                 btn btn-primary" 
		          id="basic-addon2">
		          <img src="../img/search.png"
		               width="20">

		  </button>
		</div>
       </form>
       <select class="form-control select-filter" id ="select-filter" >
       <option value="0">Lọc theo</option>
    <option value="?kytu=asc" <?= ($sortOrder == "ASC") ? 'selected' : '' ?>>Sắp xếp theo tên (A-Z)</option>
    <option value="?kytu=desc" <?= ($sortOrder == "DESC") ? 'selected' : '' ?>>Sắp xếp từ (Z-A)</option>
       </select>
       <div class="mt-5"></div>
        <?php if (isset($_GET['error'])) { ?>
          <div class="alert alert-danger" role="alert">
			  <?=htmlspecialchars($_GET['error']); ?>
		  </div>
		<?php } ?>
		<?php if (isset($_GET['success'])) { ?>
          <div class="alert alert-success" role="alert">
			  <?=htmlspecialchars($_GET['success']); ?>
		  </div>
		<?php } ?>


        <?php  if ($books == 0) { ?>
        	<div class="alert alert-warning 
        	            text-center p-5" 
        	     role="alert">
        	     <img src="img/empty.png" 
        	          width="100">
        	     <br>
			  There is no book in the database
		  </div>
        <?php }else {?>
				<!-- Main -->
                <h2 class="h2">Sản phẩm nổi bật</h2>

    		<table class="table table-bordered shadow">
			<thead>
				<tr>
					<th>#</th>
					<th>Tiêu đề</th>
					<th>Thông tin chi tiết</th>
				</tr>
			</thead>
			<tbody>
			  <?php 
			  $i = 0;
			  foreach ($books as $book) {
			    $i++;
			  ?>
			  <tr>
				<td><?=$i?></td>
				<td>
					<img width="300px"
					     src="../Uploads/cover/<?=$book['cover']?>" >
					<a  class="link-dark d-block
					           text-center"
					    href="../Uploads/files/<?=$book['file']?>">
					   <?=$book['title']?>	
					</a>
						
				</td>

				<td><?=$book['description']?></td>
			  </tr>
			  <?php } ?>
			</tbody>
		</table>
	   <?php }?>

<p class="text-center"><a href="View/seemore.php">Xem thêm &nbsp;<i class="fa fa-long-arrow-right"></i></a></p>

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
            <script>
		// JavaScript để chuyển trang khi chọn tùy chọn sắp xếp
		document.getElementById('select-filter').addEventListener('change', function () {
			var value = this.value;
			if (value !== "0") {
				window.location.href = value;
			} else {
				alert('Hãy chọn một tùy chọn lọc.');
			}
		});
	</script>
	</body>
</html>