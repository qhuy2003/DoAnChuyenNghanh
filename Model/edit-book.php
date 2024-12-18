<?php  
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	include "../config/db_conn.php";

    include "../Controller/func-validation.php";

    include "../Controller/func-file-upload.php";


    /** 
	
	**/
	if (isset($_POST['book_id'])          &&
        isset($_POST['book_title'])       &&
		isset($_POST['book_publisher']) &&
        isset($_POST['book_year_published']) &&
        isset($_POST['book_description']) &&
		isset($_POST['book_price'])       &&
        isset($_POST['book_author'])      &&
        isset($_POST['book_category'])    &&
        isset($_FILES['book_cover'])      &&
        isset($_FILES['file'])            &&
        isset($_POST['current_cover'])    &&
        isset($_POST['current_file'])) {

		/** 
	
		**/
		$id          = $_POST['book_id'];
		$title       = $_POST['book_title'];
		$publisher = $_POST['book_publisher'];
		$year_published = $_POST['book_year_published'];
		$description = $_POST['book_description'];
		$author      = $_POST['book_author'];
		$category    = $_POST['book_category'];
		$price   	 = 	$_POST['book_price'];


		//lay anh bia va file hien tai
		// tu POST và luu truu trong var
        $current_cover = $_POST['current_cover'];
        $current_file  = $_POST['current_file'];

        #simple form Validation
        $text = "Book title";
        $location = "../edit-book.php";
        $ms = "id=$id&error";
		is_empty($title, $text, $location, $ms, "");

		$text = "Book publisher";
        $location = "../edit-book.php";
        $ms = "id=$id&error";
		is_empty($description, $text, $location, $ms, "");

		$text = "Book year published";
        $location = "../edit-book.php";
        $ms = "id=$id&error";
		is_empty($description, $text, $location, $ms, "");

		$text = "Book description";
        $location = "../edit-book.php";
        $ms = "id=$id&error";
		is_empty($description, $text, $location, $ms, "");

		$text = "Book author";
        $location = "../edit-book.php";
        $ms = "id=$id&error";
		is_empty($author, $text, $location, $ms, "");

		$text = "Book category";
        $location = "../edit-book.php";
        $ms = "id=$id&error";
		is_empty($category, $text, $location, $ms, "");

		$text = "Book price";
        $location = "../edit-book.php";
        $ms = "id=$id&error";
		is_empty($price, $text, $location, $ms, "");

       
			// update anh bia

          if (!empty($_FILES['book_cover']['name'])) {
          	  /**
		          if the admin try to 
		          update both 
		      **/
		      if (!empty($_FILES['file']['name'])) {
		      	# update both here

		      	# book cover Uploading
		        $allowed_image_exs = array("jpg", "jpeg", "png");
		        $path = "cover";
		        $book_cover = upload_file($_FILES['book_cover'], $allowed_image_exs, $path);

		        # book cover Uploading
		        $allowed_file_exs = array("pdf", "docx", "pptx");
		        $path = "files";
		        $file = upload_file($_FILES['file'], $allowed_file_exs, $path);
                
                /**
				    loi khi upload
				**/
		        if ($book_cover['status'] == "error" || 
		            $file['status'] == "error") {

			    	$em = $book_cover['data'];

			    	/**
			    	  
			    	**/
			    	header("Location: ../edit-book.php?error=$em&id=$id");
			    	exit;
			    }else {
                  # anh bia gan nhat
			      $c_p_book_cover = "../uploads/cover/$current_cover";

			      # file gan naht
			      $c_p_file = "../uploads/files/$current_file";

			      # xoa 
			      unlink($c_p_book_cover);
			      unlink($c_p_file);

			      /**
		              sach moi file moi
		          **/
		           $file_URL = $file['data'];
		           $book_cover_URL = $book_cover['data'];

		            # update just the data
		          	$sql = "UPDATE books
		          	        SET title=?,
		          	            author_id=?,
		          	            description=?,
		          	            category_id=?,
								price=?,
		          	            cover=?,
		          	            file=?
		          	        WHERE id=?";
		          	$stmt = $conn->prepare($sql);
					$res  = $stmt->execute([$title, $author, $description,$price, $category,$book_cover_URL, $file_URL, $id]);

				    /**
				    neu kohng co loi
				    **/
				     if ($res) {
				     	# success message
				     	$sm = "Successfully updated!";
						header("Location: ../edit-book.php?success=$sm&id=$id");
			            exit;
				     }else{
				     	# Error message
				     	$em = "Unknown Error Occurred!";
						header("Location: ../edit-book.php?error=$em&id=$id");
			            exit;
				     }


			    }
		      }else {
		      	# chi update bookcover

		      	# book cover Uploading
		        $allowed_image_exs = array("jpg", "jpeg", "png");
		        $path = "cover";
		        $book_cover = upload_file($_FILES['book_cover'], $allowed_image_exs, $path);
                
                /**
				    If error occurred while 
				    uploading
				**/
		        if ($book_cover['status'] == "error") {

			    	$em = $book_cover['data'];

			    	/**
			    	  Redirect to '../edit-book.php' 
			    	  and passing error message & the id
			    	**/
			    	header("Location: ../edit-book.php?error=$em&id=$id");
			    	exit;
			    }else {
                  # current book cover path
			      $c_p_book_cover = "../uploads/cover/$current_cover";

			      
			      unlink($c_p_book_cover);

			      /**
		              Getting the new file name 
		              and the new book cover name 
		          **/
		           $book_cover_URL = $book_cover['data'];

		            # update data
		          	$sql = "UPDATE books
		          	        SET title=?,publisher=?,year_published=?,
		          	            author_id=?,
		          	            description=?,
								category_id=?,
									price=?,
		          	            cover=?
	
		          	        WHERE id=?";
		          	$stmt = $conn->prepare($sql);
					$res  = $stmt->execute([$title, $publisher,$year_published,$author, $description, $category,$price,$book_cover_URL, $id]);

				    /**
				      If there is no error while 
				      updating the data
				    **/
				     if ($res) {
				     	# success message
				     	$sm = "Cap nhat thanh cong!";
						header("Location: ../View/edit-book.php?success=$sm&id=$id");
			            exit;
				     }else{
				     	# Error message
				     	$em = "Unknown Error Occurred!";
						header("Location: ../View/edit-book.php?error=$em&id=$id");
			            exit;
				     }


			    }
		      }
          }
        
        //   chỉ update file

          else if(!empty($_FILES['file']['name'])){
          	# update just the file
            
            # book cover Uploading
	        $allowed_file_exs = array("pdf", "docx", "pptx");
	        $path = "files";
	        $file = upload_file($_FILES['file'], $allowed_file_exs, $path);
            
             // lỗi khi up load
	        if ($file['status'] == "error") {

		    	$em = $file['data'];

		    	// Chuyển hướng đến '../edit-book.php' và truyền thông báo lỗi và id
		    	header("Location: ../edit-book.php?error=$em&id=$id");
		    	exit;
		    }else {
              # current book cover
		      $c_p_file = "../uploads/files/$current_file";

		      # Delete from the server
		      unlink($c_p_file);

		    
	            //   tên file mới
	        
	           $file_URL = $file['data'];

	            
	          	$sql = "UPDATE books
	          	        SET title=?,publisher=?,year_published=?
	          	            author_id=?,
	          	            description=?,
	          	            category_id=?,
							price=?,
	          	            file=?
	          	        WHERE id=?";
	          	$stmt = $conn->prepare($sql);
				$res  = $stmt->execute([$title,$publisher,$year_published,$author, $description, $category,$price, $file_URL, $id]);

			    /**
			      If there is no error while 
			      updating the data
			    **/
			     if ($res) {
			     	# thanh cong 
			     	$sm = "Cap nhat thanh cong!";
					header("Location: ../edit-book.php?success=$sm&id=$id");
		            exit;
			     }else{
			     	# loi
			     	$em = "Unknown Error Occurred!";
					header("Location: ../edit-book.php?error=$em&id=$id");
		            exit;
			     }


		    }
	      
          }else {
          	# Chi update data
          	$sql = "UPDATE books
          	        SET title=?,publisher=?,year_published=?,
          	            author_id=?,
          	            description=?,
          	            category_id=?,
						price=?
          	        WHERE id=?";
          	$stmt = $conn->prepare($sql);
			$res  = $stmt->execute([$title,$publisher,$year_published	, $author, $description, $category,$price, $id]);

		
		    //   neu ko co loi trong khi upload data
		
		     if ($res) {
		     	# thanh cong
		     	$sm = "Cập nhật thành công!";
				header("Location: ../View/edit-book.php?success=$sm&id=$id");
	            exit;
		     }else{
		     	# loi
		     	$em = "Unknown Error Occurred!";
				header("Location: ../Veiw/edit-book.php?error=$em&id=$id");
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