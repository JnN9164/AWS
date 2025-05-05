<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

if (isset($_POST['add_product'])) {
  $name = $_POST['name'];
  $description = $_POST['description'];
  $price = floatval($_POST['price']);

  if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $image = $_FILES['image'];
    $image_name = preg_replace("/[^a-zA-Z0-9\.]/", "_", $image['name']);
   $image_tmp_name = $image['tmp_name'];
    $image_size = $image['size'];
    $image_type = $image['type'];


    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

    if (in_array($image_type, $allowed_types) && $image_size <= 5000000) {
      $upload_dir = '/image/';
      $absolute_dir = $_SERVER['DOCUMENT_ROOT'] . $upload_dir;
      $image_name = basename($image['name']);
      $absolute_path = $absolute_dir . $image_name;

      if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
      }

      if (move_uploaded_file($image_tmp_name, $absolute_path)) {
        
      $query = "INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssds", $name, $description, $price, $image_name);
        mysqli_stmt_execute($stmt);
        $_SESSION['message'] = "Product added successfully! 🎉";
        header("Location: index.php");
       exit();
    }

      } else {
        echo "Error uploading file.";
      }
    } else {
      echo "Invalid file type or size.";
    }
  } else {
    //echo "No image uploaded.";
  }

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Add Product</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
  <div class="container mt-5">
<div class="d-flex justify-content-between">
                            <a href="index.php" class="btn btn-secondary">← Back</a>

                        </div>
    <h2>Add Product</h2>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4" required></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Price</label>
        <input type="number" name="price" class="form-control" step="0.01" min="0" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Product Image</label>
        <input type="file" name="image" class="form-control" required>
      </div>
      <button type="submit" name="add_product" class="btn btn-success">Add Product</button>
 </form>
  </div>
</body>

</html>
