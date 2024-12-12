<?php
session_start();
include "db_conn.php"; // Kết nối cơ sở dữ liệu

// Kiểm tra nếu ID sản phẩm có trong URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Truy vấn để lấy thông tin sản phẩm dựa trên ID
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    // Nếu sản phẩm không tồn tại
    if (!$product) {
        echo "Sản phẩm không tồn tại.";
        exit();
    }
} else {
    echo "Không có ID sản phẩm.";
    exit();
}

// Kiểm tra nếu người dùng nhấn nút "Add to Cart"
if (isset($_POST['add_to_cart'])) {
    // Kiểm tra người dùng đã đăng nhập chưa
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $product_id = $_POST['product_id'];  // Lấy ID sản phẩm từ form
        $quantity = $_POST['quantity'];      // Lấy số lượng từ form

        // Kiểm tra nếu giá trị quantity hợp lệ
        if ($quantity < 1) {
            echo "Số lượng không hợp lệ.";
            exit();
        }

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $check_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $check_cart->execute([$user_id, $product_id]);

        if ($check_cart->rowCount() > 0) {
            // Cập nhật số lượng nếu sản phẩm đã tồn tại trong giỏ hàng
            $update_cart = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
            $update_cart->execute([$quantity, $user_id, $product_id]);
        } else {
            // Thêm mới sản phẩm vào giỏ hàng
            $add_to_cart = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $add_to_cart->execute([$user_id, $product_id, $quantity]);
        }

        // Quay lại trang chi tiết sản phẩm sau khi thêm vào giỏ hàng
        header("Location: product-details.php?id=" . $product_id);
        exit(); // Dừng thực thi mã sau khi điều hướng
    } else {
        // Điều hướng đến trang đăng nhập nếu người dùng chưa đăng nhập
        header("Location: login.php");
        exit();
    }
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
</head>
<body class="is-preload">
    <div id="wrapper">

        <!-- Header -->
        <header id="header">
            <div class="inner">
                <a href="../index.php" class="logo">
                    <span class="fa fa-book"></span> <span class="title">Nhà sách Quốc Huy</span>
                </a>
                <!-- Giỏ hàng -->
                <?php
                if (isset($_SESSION['user_id'])) {
                    $user_id = $_SESSION['user_id'];
                    $count_cart_items = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
                    $count_cart_items->execute([$user_id]);
                    $total_cart_items = $count_cart_items->rowCount();
                } else {
                    $total_cart_items = 0;
                }
                ?>
                <a href="cart.php" class="cart-link">
                    <i class="fa-solid fa-cart-shopping"></i> Giỏ Hàng <span><?= $total_cart_items; ?></span>
                </a>
            </div>
        </header>

        <!-- Main -->
        <div id="main">
            <div class="inner">
                <h1><?php echo $product['title']; ?></h1>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-5">
                            <img src="images/<?php echo $product['cover']; ?>" class="img-fluid" alt="">
                        </div>
                        <div class="col-md-7">
                            <p><strong><?php echo $product['title']; ?></strong></p>
                            <p><?php echo $product['description']; ?></p>
                            
                            <!-- Form thêm vào giỏ hàng -->
                            <form action="add-to-cart.php" method="POST">
                                <div class="form-group">
                                    <label for="quantity">Số lượng</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" required>
                                </div>
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>"> <!-- Truyền ID sản phẩm -->
                                <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
