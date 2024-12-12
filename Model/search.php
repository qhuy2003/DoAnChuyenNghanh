<?php 
session_start();


if (!isset($_GET['key']) || empty($_GET['key'])) {
	header("Location: index.php");
	exit;
}
$key = $_GET['key'];

# Database Connection File
include "View/db_conn.php";


include "View/php/func-book.php";
$books = search_books($conn, $key);


include "View/php/func-author.php";
$authors = get_all_author($conn);


include "View/php/func-category.php";
$categories = get_all_categories($conn);

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Nhà sách Quốc Huy</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
		<link rel="stylesheet" href="View/assets/bootstrap/css/bootstrap.min.css" />
		<link rel="stylesheet" href="View/assets/css/main.css" />
		<noscript><link rel="stylesheet" href="View/assets/css/noscript.css" /></noscript>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>


</head>
<body>
	
	<header id="header">
						<div class="inner">

							<!-- Logo -->
								<a href="index.php" class="logo">
									<span class="fa fa-book"></span> <span class="title">Nhà sách Quốc Huy</span>
									
								</a>
								<form action="search.php"
             method="get" 
             style="width: 100%;padding:20px; max-width:300px">

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
		          <img src="View/images/search.png"
		               width="20">

		  </button>
		</div>
       </form>
							
							<!-- Nav -->
								<nav>
									<ul>
										<li><a href="#menu">Menu</a></li>
									</ul>
									
								</nav>

						</div>
					</header>
					<nav id="menu">
						<h2>Menu</h2>
						<ul>
							<li><a href="index.php" class="active">Trang chủ</a></li>

							<li><a href="View/products.html">Sản phẩm</a></li>

							<li><a href="View/checkout.html">Giỏ hàng</a></li>

							<li>
								<a href="#" class="dropdown-toggle">Thể loại</a>

								<ul>
									<li><a href="View/about.html">About Us</a></li>
									<li><a href="View/blog.html">Blog</a></li>
									<li><a href="View/testimonials.html">Testimonials</a></li>
									<li><a href="View/terms.html">Terms</a></li>
									
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
					<div class="container">	
		Search result for <b><?=$key?></b>

		<div class="d-flex pt-3">
			<?php if ($books == 0){ ?>
				<div class="alert alert-warning 
        	            text-center p-5 pdf-list" 
        	     role="alert">
        	     <img src="View/images/empty-search.png" 
        	          width="100">
        	     <br>
				  Từ khóa <b>"<?=$key?>"</b> không có dữ liệu 
			  </div>
			<?php }else{ ?>
			<div class="pdf-list d-flex flex-wrap">
				<?php foreach ($books as $book) { ?>
				<div class="card m-1">
					<img src="uploads/cover/<?=$book['cover']?>"
					     class="card-img-top">
					<div class="card-body">
						<h5 class="card-title"	>
							<?=$book['title']?>
						</h5>
						<p class="card-text">
							<i><b>By:
								<?php foreach($authors as $author){ 
									if ($author['id'] == $book['author_id']) {
										echo $author['name'];
										break;
									}
								?>

								<?php } ?>
							<br></b></i>
							<?=$book['description']?>
							<br><i><b>Category:
								<?php foreach($categories as $category){ 
									if ($category['id'] == $book['category_id']) {
										echo $category['name'];
										break;
									}
								?>

								<?php } ?>
							<br></b></i>
						</p>
                       <a href="/Uploads/files/<?=$book['file']?>"
                          class="btn btn-success">Xem</a>

                        <a href="/Uploads/files/<?=$book['file']?>"
                          class="btn btn-primary"
                          download="<?=$book['title']?>">Download</a>
					</div>
				</div>
				<?php } ?>
			</div>
		<?php } ?>
		</div>
	</div>
	<script src="View/assets/js/jquery.min.js"></script>
			<script src="View/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
			<script src="View/assets/js/jquery.scrolly.min.js"></script>
			<script src="View/assets/js/jquery.scrollex.min.js"></script>
			<script src="View/assets/js/main.js"></script>
</body>
</html>