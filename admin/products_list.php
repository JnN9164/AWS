<?php
session_start();
include '../config.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}


$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

$imgPath = "../image/";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product List - Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f8fa;
    }

    .navbar {
      background-color: #003366;
    }
 .navbar-brand, .nav-link {
      color: #ffffff !important;
      font-weight: 600;
    }

    .nav-link:hover {
      color: #ffc107 !important;
    }

    .container {
      margin-top: 80px;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
 h2 {
      font-weight: 600;
      color: #003366;
      margin-bottom: 30px;
    }

    .btn-success {
      background-color: #28a745;
      border: none;
      font-weight: 600;
    }

    .btn-warning {
      font-weight: 600;
    }
 .btn-danger {
      font-weight: 600;
    }

    table img {
      max-width: 100px;
      border-radius: 8px;
    }
  </style>
</head>

<body>

<!-- Admin Header -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Admin Panel</a>
 <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="products_list.php">Products</a></li>
        <li class="nav-item"><a class="nav-link" href="view_orders.php">Orders</a></li>
        <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>


<div class="container">
  <h2 class="text-center">Product List</h2>

  <?php if (isset($_SESSION['message'])): ?>
  <div class="alert alert-info alert-dismissible fade show" role="alert">
      <?= $_SESSION['message']; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Description</th>
          <th>Price (RM)</th>
          <th>Image</th>
          <th>Actions</th>
 </tr>
      </thead>
      <tbody>
        <?php while ($product = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= $product['id']; ?></td>
            <td><?= htmlspecialchars($product['name']); ?></td>
            <td><?= htmlspecialchars($product['description']); ?></td>
            <td>RM <?= number_format($product['price'], 2); ?></td>
            <td>
              <?php if ($product['image']): ?>
                <img src="<?= $imgPath . $product['image']; ?>" alt="Product Image">
              <?php else: ?>
                No Image
              <?php endif; ?>
            </td>
            <td>
   <a href="edit_product.php?id=<?= $product['id']; ?>" class="btn btn-warning btn-sm mb-1">Edit</a>
              <a href="delete_product.php?id=<?= $product['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to d>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <div class="text-center mt-4">
    <a href="add_product.php" class="btn btn-success">➕ Add New Product</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
