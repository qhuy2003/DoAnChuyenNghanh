<?php  
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	# Database Connection File
	include "../config/db_conn.php";


    // /** 
	//   check if category 
	//   name is submitted
	// **/
	if (isset($_POST['category_name'])) {
		
		$name = $_POST['category_name'];

		# form Validation
		if (empty($name)) {
			$em = " chưa nhập tên thể loại";
			header("Location: ../add-category.php?error=$em");
            exit;
		}else {
			# Them vao databse
			$sql  = "INSERT INTO categories (name)
			         VALUES (?)";
			$stmt = $conn->prepare($sql);
			$res  = $stmt->execute([$name]);

			/**
		      
		    **/
		     if ($res) {
		     	
		     	$sm = "Thêm thành công!";
				header("Location: ../View/add-category.php?success=$sm");
	            exit;
		     }else{
				$em = "xảy ra lỗi!";			
				header("Location: ../add-category.php?error=$em");
	            exit;
		     }
		}
	}else {
      header("Location: ../View/admin.php");
      exit;
	}

}else{
	header("Location: ../View/login.php");
	exit;
}