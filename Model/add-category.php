<?php  
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	# Database Connection File
	include "../db_conn.php";


    // /** 
	//   check if category 
	//   name is submitted
	// **/
	if (isset($_POST['category_name'])) {
		
		$name = $_POST['category_name'];

		# form Validation
		if (empty($name)) {
			$em = "The category name is required";
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
				header("Location: ../add-category.php?success=$sm");
	            exit;
		     }else{
		     	$em = "Không hợp lệ!";
				header("Location: ../add-category.php?error=$em");
	            exit;
		     }
		}
	}else {
      header("Location: ../admin.php");
      exit;
	}

}else{
  header("Location: ../login.php");
  exit;
}