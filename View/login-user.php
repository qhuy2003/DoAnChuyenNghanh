<?php  
session_start();
include '../config/db_conn.php';
// init configuration
require_once '../vendor/autoload.php';

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId('676177425201-fetlcecpsng4ohg29sqqjr55vkq1ri3k.apps.googleusercontent.com'); 
$client->setClientSecret('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'); 
$client->setRedirectUri('http://localhost:3000/View/login-user.php'); 
$client->addScope('email');
$client->addScope('profile');
$client->setHttpClient(new \GuzzleHttp\Client([
    'verify' => realpath('C:/wamp64/bin/php/php7.4.33/extras/ssl/cacert.pem')
]));

// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    // Kiểm tra xem có lỗi không
    if (isset($token['error'])) {
        die('Error: ' . $token['error_description']);
    }

    // Lưu token vào client
    $client->setAccessToken($token['access_token']);
    
    // Lấy thông tin người dùng từ Google
    $google_oauth = new Google\Service\Oauth2($client);  
    $google_account_info = $google_oauth->userinfo->get();

	$google_user_id = $google_account_info->id;
	$email = $google_account_info->email;
	$name = $google_account_info->name;

	// Kiểm tra nếu user chưa tồn tại trong cơ sở dữ liệu
	$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
	$stmt->execute([$email]);

	if ($stmt->rowCount() == 0) {
	// Nếu user không tồn tại, thêm bản ghi mới vào users
	$insert_user = $conn->prepare("INSERT INTO users (google_id, email) VALUES (?, ?)");
	$insert_user->execute([$google_user_id, $email]);

	// Lấy ID vừa tạo
	$user_id = $conn->lastInsertId();
	} else {
	// Nếu đã tồn tại, lấy ID hiện tại
	$user = $stmt->fetch(PDO::FETCH_ASSOC);
	$user_id = $user['id'];
	}

	// Lưu user_id vào session
	$_SESSION['user_id'] = $user_id;
	$_SESSION['email'] = $email;
	// Chuyển hướng về trang index.php
	header('Location: view-product.php');
	exit();
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
	<link rel="stylesheet" href="assets/css/main.css" />
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

<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
     <form class="p-5 rounded shadow" style="max-width: 30rem; width: 100%" action="../Controller/login.php" method="POST">
	 <h1 class="text-center display-4 pb-5">Đăng nhập</h1>

     	<label> <i class="fa-solid fa-envelope"></i> Email</label><br>
     	<input type="text" name="email" class="form-control"><br>
     	<label><i class="fa-solid fa-lock"></i> Password</label><br>
     	<input type="password" name="password" class="form-control"><br><br>
		 <div class="btn-regis" style="padding-left: 155px;">
        <button type="submit" class="btn btn-success" name="submit" style="font-size: 20px; padding: 15px 25px; border-radius: 25px;">
            <i class="fa-solid fa-arrow-right"></i>
        </div>
    </button>

    <center><a href="<?php echo $client->createAuthUrl() ?>">  
        <img src="images/google.png" width="20"> Đăng nhập qua Google
    </a></center>

	<div class="d-flex justify-content-center align-items-center" style="padding-left: 15px;">
		<a href="registration.php"><strong>Tạo tài khoản</strong> </a>
	</div>
     </form>
</div>
</body>
</html>
