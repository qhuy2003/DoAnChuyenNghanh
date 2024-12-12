<?php 

session_start();

session_unset();//xoa tat ca  bien hien tai, luu tru trong session
session_destroy();//xoa du lieu tren may chu 

header("Location: ../index.php");
exit;