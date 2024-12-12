<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
	<link rel="stylesheet" href="assets/css/main.css" />
	<title>Đăng nhập</title>
</head>
<body>

<header id="header">
						<div class="inner" style="padding-left:20px;">

							<!-- Logo -->
								<a href="../index.php" class="logo">
									<span class="fa fa-book"></span> <span class="title">Nhà sách Quốc Huy</span>
									
								</a>
						</div>
</header>


	  <div class="d-flex justify-content-center align-items-center"
	  style="min-height: 100vh;">
	<form class="p-5 rounded shadow"style="max-width: 30rem; width: 100%" action="php/signup.php"	method="POST" >
	<?php if (isset($_GET['success'])) { ?>
		<div class="alert alert-success" role="alert">
			  <?=htmlspecialchars($_GET['success']); ?>
		  </div>
      <?php } ?>
	<?php if (isset($_GET['error'])) { ?>
		<div class="alert alert-danger" role="alert">
			  <?=htmlspecialchars($_GET['error']); ?>
		  </div>
      <?php } ?>
	  	  <div class="d-flex justify-content-center align-items-center">
	  <h3>Đăng kí tài khoản mới</h3>
	</div>
     	<label>  <i class="fa-solid fa-user"></i> Họ </label><br>
     	 <input type="text" name="first_name" class="form-control" placeholder="Họ người dùng"><br>
     	<label>   <i class="fa-solid fa-user"></i>  Tên</label><br>
     	 <input type="text" name="last_name"class="form-control"placeholder="Tên người dùng"><br>
     	<label><i class="fa-solid fa-envelope"></i> Email</label><br>
     	<input type="text" name="email"class="form-control"placeholder="Email"><br>
     	<label><i class="fa-solid fa-lock"></i> Mật khẩu</label><br>
     	<input type="password" name="password"class="form-control"placeholder="Mật khẩu"><br>
     	<label><i class="fa-solid fa-lock"></i> Nhập lại mật khẩu</label><br>
     	<input type="password" name="confirm_password"class="form-control"placeholder="Nhập lại mật khẩu"><br><br>
		 <div class="btn-regis" style="padding-left: 155px;">
    <button type="submit" class="btn btn-success" style="font-size: 20px; padding: 15px 25px; border-radius: 25px;">
        <i class="fa-solid fa-arrow-right"></i>
    </button>
</div>
     </form>
	 </div>
</body>
</html>