<?php  
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	# Database Connection File
	include "../config/db_conn.php";


    /** 
	  check if author 
	  name is submitted
	**/
	if (isset($_POST['author_name'])) {
		/** 
		Get data from POST request 
		and store it in var
		**/
		$name = $_POST['author_name'];

		#simple form Validation
		if (empty($name)) {
			$em = "The author name is required";
			header("Location: ../View/add-author.php?error=$em");
            exit;
		}else {
			# Insert Into Database
			$sql  = "INSERT INTO authors (name)
			         VALUES (?)";
			$stmt = $conn->prepare($sql);
			$res  = $stmt->execute([$name]);

			/**
		      If there is no error while 
		      inserting the data
		    **/
		     if ($res) {
		     	# success message
		     	$sm = "Them thanh cong!";
				header("Location: ../View/add-author.php?success=$sm");
	            exit;
		     }else{
		     	# Error message
		     	$em = "Khong the them !";
				header("Location: ../View/add-author.php?error=$em");
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