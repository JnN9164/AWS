<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

$query = "SELECT orders.*, users.username
          FROM orders
          JOIN users ON orders.user_id = users.id
          ORDER BY orders.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Orders - Admin</title>
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

    .table th, .table td {
      vertical-align: middle;
    }

    .btn-info {
      font-weight: 600;
    }
  </style>
</head>

<body>

<!-- Admin Navbar -->
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
</nav>

<!-- Content -->
<div class="container">
<div class="d-flex justify-content-between">
                            <a href="index.php" class="btn btn-secondary">← Back</a>

                        </div>
  <h2 class="text-center">Order Management</h2>

  <div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
      <thead class="table-dark">
        <tr>
          <th>Order ID</th>
<th>User Name</th>
          <th>Total Price (RM)</th>
          <th>Date</th>
          <th>Details</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($order = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= $order['id']; ?></td>
             <td><?= htmlspecialchars($order['username']); ?></td>
            <td>RM <?= number_format($order['total_price'], 2); ?></td>
            <td><?= $order['created_at']; ?></td>
            <td>
              <a href="admin_order_details.php?order_id=<?= $order['id']; ?>" class="btn btn-info btn-sm">View</a>

            </td>
  </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
