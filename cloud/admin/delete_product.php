<?php
session_start();
include '../config.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}


if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
  $product_id = $_GET['id'];

 
  $query = "DELETE FROM products WHERE id = ?";
  $stmt = mysqli_prepare($conn, $query);
  
  if ($stmt) {
   
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    
  
    if (mysqli_stmt_execute($stmt)) {
      $_SESSION['message'] = "Product deleted successfully!";
    } else {
      $_SESSION['message'] = "Error deleting product.";
    }
  
    mysqli_stmt_close($stmt);
  } else {
    $_SESSION['message'] = "Error preparing query.";
  }
} else {
  $_SESSION['message'] = "Invalid product ID.";
}

header("Location: products_list.php");  
exit();
?>
