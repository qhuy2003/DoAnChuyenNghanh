<?php  
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	# Database Connection File
	include "../config/db_conn.php";


    /** 
	  check if category 
	  name is submitted
	**/
	if (isset($_POST['category_name']) &&
        isset($_POST['category_id'])) {
		/** 
		Get data from POST request 
		and store them in var
		**/
		$name = $_POST['category_name'];
		$id = $_POST['category_id'];

		#simple form Validation
		if (empty($name)) {
			$em = " chưa nhập tên thể loại";
			header("Location: ../edit-category.php?error=$em&id=$id");
            exit;
		}else {
			# UPDATE the Database
			$sql  = "UPDATE categories 
			         SET name=?
			         WHERE id=?";
			$stmt = $conn->prepare($sql);
			$res  = $stmt->execute([$name, $id]);

			/**
		      If there is no error while 
		      updating the data
		    **/
		     if ($res) {
		     	# success message
		     	$sm = "Cập nhật thành công!";
				header("Location: ../View/edit-category.php?success=$sm&id=$id");
	            exit;
		     }else{
		     	# Error message
		     	$em = "xảy ra lỗi!";
				header("Location: ../View/edit-category.php?error=$em&id=$id");
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