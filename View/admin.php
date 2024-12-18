<?php  
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	# Database Connection File
	include "../config/db_conn.php";

	# Book helper function
	include "../Controller/func-book.php";
    $books = get_all_books($conn);

    # author helper function
	include "../Controller/func-author.php";
    $authors = get_all_author($conn);

    # Category helper function
	include "../Controller/func-category.php";
    $categories = get_all_categories($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ADMIN</title>

    <!-- bootstrap 5 CDN-->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <!-- bootstrap 5 Js bundle CDN-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>

</head>
<body>
	<div class="container">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
		  <div class="container-fluid">
		    <a class="navbar-brand" href="admin.php">Admin</a>
		    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		      <span class="navbar-toggler-icon"></span>
		    </button>
		    <div class="collapse navbar-collapse" 
		         id="navbarSupportedContent">
		      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
		       
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="add-book.php">Thêm sách</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="add-category.php">Thêm thể loại</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="add-author.php">Thêm tác giả</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="../Controller/logout.php">Đăng xuất</a>
		        </li>
		      </ul>
		    </div>
		  </div>
		</nav>

       <form action="search.php" method="get" style="width: 100%; max-width: 30rem">
       	<div class="input-group my-5">
		  <input type="text" class="form-control" name="key"  placeholder="Tìm kiếm sách" aria-label="Search Book..." aria-describedby="basic-addon2">
		  <button class="input-group-text btn btn-primary"  id="basic-addon2">
		         <img src="images/search.png"width="20">
		  </button>
		</div>
       </form>
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
			  không có sách trong cơ sở dử liệu
		  </div>
        <?php }else {?>


        <!-- List of all books -->
		<h4>Tất cả sách</h4>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Tiêu đề</th>
            <th>Nhà xuất bản</th>
            <th>Năm xuất bản</th>
            <th>Tác giả</th>
            <th>Thông tin chi tiết</th>
            <th>Thể loại</th>
            <th>Giá tiền</th>
            <th>Tác vụ</th>
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
                <img width="100" src="../Uploads/cover/<?=$book['cover']?>" >
                <a class="link-dark d-block text-center" href="../Uploads/files/<?=$book['file']?>">
                    <?=$book['title']?> 
                </a>
            </td>
            <td><?=$book['publisher']?></td>
            <td><?=$book['year_published']?></td>
            <td>
                <?php 
                if ($authors == 0) {
                    echo "Undefined";
                } else { 
                    foreach ($authors as $author) {
                        if ($author['id'] == $book['author_id']) {
                            echo $author['name'];
                        }
                    }
                }
                ?>
            </td>
            <td>
                <div class="des">
                    <?= mb_strlen($book['description']) > 100 ? mb_substr($book['description'], 0, 100) . '...' : $book['description']; ?>
                </div>
            </td>
            <td>
                <?php 
                if ($categories == 0) {
                    echo "Undefined";
                } else { 
                    foreach ($categories as $category) {
                        if ($category['id'] == $book['category_id']) {
                            echo $category['name'];
                        }
                    }
                }
                ?>
            </td>
            <td><?=$book['price']?></td>
            <td>
                <a href="edit-book.php?id=<?=$book['id']?>" class="btn btn-warning">Sửa</a>
                <a href="../Model/delete-book.php?id=<?=$book['id']?>" class="btn btn-danger">Xóa</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

			</div>
	   <?php }?>

        <?php  if ($categories == 0) { ?>
        	<div class="alert alert-warning 
        	            text-center p-5" 
        	     role="alert">
        	     <img src="img/empty.png" 
        	          width="100">
        	     <br>
			  There is no category in the database
		    </div>
        <?php }else {?>


	    <!-- List of all categories -->
		<h4>Thể loại</h4>
		<table class="table table-bordered shadow">
			<thead>
				<tr>
					<th>#</th>
					<th>Tên thể loại</th>
					<th>Tác vụ</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$j = 0;
				foreach ($categories as $category ) {
				$j++;	
				?>
				<tr>
					<td><?=$j?></td>
					<td><?=$category['name']?></td>
					<td>
						<a href="edit-category.php?id=<?=$category['id']?>" 
						   class="btn btn-warning">
						   Sửa</a>

						<a href="../Model/delete-category.php?id=<?=$category['id']?>" 
						   class="btn btn-danger">
					       Xóa</a>
					</td>
				</tr>
			    <?php } ?>
			</tbody>
		</table>
	    <?php } ?>

	    <?php  if ($authors == 0) { ?>
        	<div class="alert alert-warning 
        	            text-center p-5" 
        	     role="alert">
        	     <img src="img/empty.png">
        	          width="100">
        	     <br>
			  không có dữ liệu tác giả
		    </div>
        <?php }else {?>
	    <!-- List of all Authors -->

		<h4 class="mt-5">Tất cả tác giả</h4>
         <table class="table table-bordered shadow">
			<thead>
				<tr>
					<th>#</th>
					<th>Tên tác giả</th>
					<th>Tác vụ</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$k = 0;
				foreach ($authors as $author ) {
				$k++;	
				?>
				<tr>
					<td><?=$k?></td>
					<td><?=$author['name']?></td>
					<td>
						<a href="../View/edit-author.php?id=<?=$author['id']?>" 
						   class="btn btn-warning">
						   Sửa</a>

						<a href="../Model/delete-author.php?id=<?=$author['id']?>" 
						   class="btn btn-danger">
					       Xóa</a>
					</td>
				</tr>
			    <?php } ?>
			</tbody>
		</table> 
		<?php } ?>
	</div>
</body>
</html>

<?php }else{
  header("Location: login.php");
  exit;
} ?>