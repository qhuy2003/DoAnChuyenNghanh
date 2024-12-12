<?php  

# kiem tra du lieu dau vao va thong bao loi cua form
function is_empty($var, $text, $location, $ms, $data){
   if (empty($var)) {
   	 # thong bao loi
   	 $em = "Không được để trống ".$text;
   	 header("Location: $location?$ms=$em&$data");
   	 exit;
   }
   return 0;
}
?>