<?php
session_start();
include '../config.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}


if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
  $product_id = $_GET['id'];

 
  $query = "SELECT * FROM products WHERE id = '$product_id' LIMIT 1";
  $result = mysqli_query($conn, $query);

  
  if (mysqli_num_rows($result) == 0) {
    echo "Product not found.";
    exit();
  }

  $product = mysqli_fetch_assoc($result);
} else {
  echo "Invalid product ID specified.";
  exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $description = $_POST['description'];
  $price = $_POST['price'];
  $image = $_FILES['image']['name'];

  
  if ($image != "") {
    $target_dir = "../cloud/uploads/"; 
    $target_file = $target_dir . basename($image);

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
      echo "Error uploading image.";
      exit();
    }
  } else {
    $image = $product['image'];
  }

  
  $update_query = "UPDATE products SET name = '$name', description = '$description', price = '$price', image = '$image' WHERE id = '$product_id'";

  if (mysqli_query($conn, $update_query)) {
    $_SESSION['message'] = "Product updated successfully!";
    header("Location: products_list.php");  
    exit();
  } else {
    echo "Error updating product: " . mysqli_error($conn);
  }
}

$imgPath = "../cloud/uploads/";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Edit Product</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
  <div class="container mt-5">
    <h2>Edit Product</h2>

   
    <?php if (isset($_SESSION['message'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    
    <form action="edit_product.php?id=<?= $product['id'] ?>" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="name" class="form-label">Product Name</label>
        <input type="text" name="name" id="name" class="form-control" value="<?= $product['name'] ?>" required>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" class="form-control" rows="3" required><?= $product['description'] ?></textarea>
      </div>
      <div class="mb-3">
        <label for="price" class="form-label">Price (RM)</label>
        <input type="number" name="price" id="price" class="form-control" value="<?= $product['price'] ?>" step="0.01" required>
      </div>

      
      <?php if ($product['image']): ?>
        <div class="mb-3">
          <label for="current_image" class="form-label">Current Image</label>
          <img src="<?= $imgPath . $product['image']; ?>"  alt="Current Product Image" class="img-fluid" style="max-width: 200px;">
        </div>
      <?php endif; ?>

      <div class="mb-3">
        <label for="image" class="form-label">Product Image</label>
        <input type="file" name="image" id="image" class="form-control">
        <small class="form-text text-muted">Leave blank if you don't want to change the image.</small>
      </div>
      <div class="mb-3">
        <button type="submit" class="btn btn-primary">Update Product</button>
      </div>
    </form>
  </div>
</body>

</html>