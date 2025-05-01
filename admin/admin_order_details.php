<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}

if (!isset($_GET['order_id'])) {
  header("Location: view_orders.php");
  exit();
}

$order_id = intval($_GET['order_id']);

$order_query = mysqli_query($conn, "SELECT * FROM orders WHERE id = $order_id");
$order = mysqli_fetch_assoc($order_query);

if (!$order) {
  echo "<div style='padding: 50px; text-align: center;'>Order not found.</div>";
  exit();
}

$items_query = mysqli_query($conn, "SELECT order_items.*, products.name, products.price
  FROM order_items
  JOIN products ON order_items.product_id = products.id
  WHERE order_items.order_id = $order_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Details - Graduation Store</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f5f8fa;
      color: #333;
    }

    .navbar {
      background-color: #2c3e50;
    }

    .navbar-brand {
      font-size: 1.8rem;
      font-weight: 600;
    }

    .nav-link {
      color: #ecf0f1 !important;
      font-weight: 500;
    }

    .nav-link:hover {
      color: #3498db !important;
      text-decoration: underline;
    }

    .container {
      padding: 80px 15px;
    }

    h2 {
      font-size: 2.5rem;
      font-weight: 600;
      color: #34495e;
      margin-bottom: 30px;
    }

    .table {
      background-color: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .table th {
      background-color: #34495e;
      color: white;
      font-weight: 600;
      text-align: center;
    }

    .table td {
      vertical-align: middle;
      text-align: center;
    }

    .btn-info {
      background-color: #3498db;
      border: none;
      border-radius: 8px;
      padding: 8px 16px;
      font-weight: 500;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-info:hover {
      background-color: #2980b9;
      transform: scale(1.05);
    }

    @media (max-width: 768px) {
      .table-responsive {
        overflow-x: auto;
      }
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Graduation Store</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
        <li class="nav-item"><a class="nav-link" href="order_history.php">Orders</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h2>Order Details</h2>

  <div class="mb-4">
    <strong>Order ID:</strong> <?php echo $order['id']; ?><br>
    <strong>Order Date:</strong> <?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?><br>
    <strong>Payment Method:</strong> <?php echo ucfirst($order['payment_method']); ?><br>
    <strong>Total:</strong> RM <?php echo number_format($order['total_price'], 2); ?>
  </div>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Product</th>
        <th>Price (RM)</th>
        <th>Quantity</th>
        <th>Subtotal (RM)</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($item = mysqli_fetch_assoc($items_query)) { ?>
      <tr>
        <td><?php echo htmlspecialchars($item['name']); ?></td>
        <td><?php echo number_format($item['price'], 2); ?></td>
        <td><?php echo $item['quantity']; ?></td>
        <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>

  <a href = "view_orders.php" class="btn btn-secondary mt-3">Back to Orders</a>
</div>
</body>
</html>
