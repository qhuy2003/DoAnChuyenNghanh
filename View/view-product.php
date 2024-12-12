<?php
include '../config/db_conn.php';
	# Book helper function
   session_start();
  if (!isset($_SESSION['user_id'])) {
    echo "<script>
        alert('Vui lòng đăng nhập để tiếp tục mua hàng!');
        window.location.href = 'login-user.php';
    </script>";
    exit; // Dừng thực hiện mã phía sau
}
if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];

   // Kiểm tra và hiển thị thông báo chỉ một lần
   if (!isset($_SESSION['login_success_shown'])) {
       $success_msg[] = 'Đăng nhập thành công!';
       $_SESSION['login_success_shown'] = true; // Đánh dấu rằng thông báo đã hiển thị
   }
}
   
  # Database Connection File
  # Book helper function
  include "../Controller/func-book.php";
   $books = get_all_books($conn);



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
$order = isset($_GET['order']) && in_array($_GET['order'], ['ASC', 'DESC']) ? $_GET['order'] : 'ASC'; // Mặc định ASC nếu không hợp lệ
$select_products = $conn->prepare("SELECT * FROM `books` ORDER BY `price` $order");
$select_products->execute();
// Giả sử "Văn học" có ID là 1 trong bảng thể loại
$category_name = 'Văn học'; // Hoặc dùng ID nếu bạn biết rõ
$select_books = $conn->prepare("
    SELECT b.* 
    FROM books AS b
    JOIN categories AS c ON b.category_id = c.id
    WHERE c.name = ?
");
$select_books->execute([$category_name]);

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
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>

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
            <a class="dropdown-item" href="logout-user.php">Đăng xuất</a>
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

<section class="products">
   <h1 class="heading">Tất cả sản phẩm</h1>
   
   <!-- sap xep gia  -->
   <div class="dropdown show" style="margin-left: 75rem; padding:5px;">
  <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
   Sắp xếp theo  giá   
  </a>

  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
  <div class="sort-options">
  <a href="?order=ASC" class="dropdown-item" <?= $order === 'ASC' ? 'active' : '' ?>>Sắp xếp giá: Thấp đến Cao</a>
   <a href="?order=DESC" class="dropdown-item" <?= $order === 'DESC' ? 'active' : '' ?>>Sắp xếp giá: Cao đến Thấp</a>
</div>
  </div>
</div>

   

<!-- hien thi san pham -->
   <div class="box-container">

   <?php 
      if($select_products->rowCount() > 0){
         while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="POST" class="box">
      <img src="../Uploads/cover/<?= $fetch_product['cover']; ?>" class="image" alt="Ảnh sản phẩm">
      <a href="" ><h3 class="name"><?= htmlspecialchars($fetch_product['title'], ENT_QUOTES, 'UTF-8'); ?></h3></a>
      <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>">
      <div class="flex">
         <p class="price"><?= number_format($fetch_product['price'], 0, ',', '.'); ?> đ</p>
         <h3>Số lượng</h3>
         <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty">
      </div>
      <button type="submit" name="add_to_cart" class="btn">
         <i class="fa-solid fa-cart-shopping"></i> Thêm Vào Giỏ Hàng
      </button>
      <a href="checkout.php?id=<?= $fetch_product['id']; ?>" class="delete-btn">Mua Ngay</a>
   </form>
   <?php
         }
      } else {
         echo '<p class="empty">Không tìm thấy sản phẩm!</p>';
      }
   ?>

   </div>
</section>

</body>
</html>


<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery.scrolly.min.js"></script>
<script src="assets/js/jquery.scrollex.min.js"></script>
<script src="assets/js/main.js"></script>

<?php include 'components/alert.php'; ?>
