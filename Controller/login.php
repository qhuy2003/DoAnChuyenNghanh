<?php 
session_start();

function validate_input($data){
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


if (isset($_POST['email'])     &&
    isset($_POST['password'])) {

	include '../config/db_conn.php';

	$email = validate_input($_POST['email']);
	$password = validate_input($_POST['password']);


    if (empty($email)) {
		$errorM = "Email is required";
		header("Location: ../login-user.php?error=$errorM");
	}else if (empty($password)) {
		$errorM = "Password is required";
		header("Location: ../login-user.php?error=$errorM");
	}else {

        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $conn->prepare($sql);
		
        $stmt->execute([$email]);
        if ($stmt->rowCount() == 1) {
        	$user = $stmt->fetch();
        	$first_name = $user['first_name'];
        	$db_email = $user['email'];
        	$id = $user['id'];
        	$hash_password = $user['password'];
            if ($email === $db_email) {
            	if (password_verify($password, $hash_password)) {
            		  $_SESSION['first_name'] = $first_name;
            		  $_SESSION['email'] = $email;
            		  $_SESSION['user_id'] = $id;
            		  header("Location: ../View/view-product.php");
            	}else {
		        	$warning_msg[] = "sai tên hoặc mật khẩu";
				    header("Location: ../login-user.php?error=$warning_msg");
			    }
            }else {
	        	$warning_msg[] =  "sai tên hoặc mật khẩu";
			    header("Location: ../login-user.php?error=$warning_msg");
		    }

        }else {
        	$warning_msg[] =  "sai tên hoặc mật khẩu";
		    header("Location: ../login-user.php?error=$warning_msg");
	    }
	}
}else {
	header("Location: ../login-user.php");
}
?>
<?php include 'components/alert.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/script.js"></script>
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/jquery.scrolly.min.js"></script>
<script src="../assets/js/jquery.scrollex.min.js"></script>
<script src="../assets/js/main.js"></script>