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
	if (isset($_POST['author_name']) &&
        isset($_POST['author_id'])) {
		/** 
		Get data from POST request 
		and store them in var
		**/
		$name = $_POST['author_name'];
		$id = $_POST['author_id'];

		#simple form Validation
		if (empty($name)) {
			$em = "Chưa nhập tên tác giả";
			header("Location: ../View/edit-author.php?error=$em&id=$id");
            exit;
		}else {
			# UPDATE the Database
			$sql  = "UPDATE authors 
			         SET name=?
			         WHERE id=?";
			$stmt = $conn->prepare($sql);
			$res  = $stmt->execute([$name, $id]);

			/**
		      If there is no error while 
		      inserting the data
		    **/
		     if ($res) {
		     	# success message
		     	$sm = "Cập nhật thành công!";
				header("Location: ../View/edit-author.php?success=$sm&id=$id");
	            exit;
		     }else{
		     	# Error message
		     	$em = "Xảy ra lỗi!";
				header("Location: ../View/edit-author.php?error=$em&id=$id");
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