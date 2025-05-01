<?php
session_start();
include 'config.php';

if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}


if (isset($_POST['add_to_cart'])) {
  $product_id = $_POST['product_id'];
  $quantity = $_POST['quantity'];

  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] += $quantity;
  } else {
    $_SESSION['cart'][$product_id] = $quantity;
  }

  header("Location: cart.php");
  exit();
}


if (isset($_GET['remove'])) {
  $remove_id = $_GET['remove'];
  unset($_SESSION['cart'][$remove_id]);
  header("Location: cart.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Shopping Cart</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
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

    .btn-danger {
      background-color: #e74c3c;
      border: none;
      border-radius: 8px;
      padding: 8px 16px;
      transition: background-color 0.3s ease;
    }

    .btn-danger:hover {
      background-color: #c0392b;
    }

    .btn-primary {
      background-color: #3498db;
      border: none;
      border-radius: 8px;
      padding: 12px 24px;
      font-weight: 600;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #2980b9;
      transform: scale(1.05);
    }

    .total-section {
      background: #ffffff;
      padding: 20px;
      margin-top: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      text-align: right;
    }

    .total-section h4 {
      font-size: 1.8rem;
      color: #27ae60;
      font-weight: 700;
    }

    @media (max-width: 768px) {
      .table-responsive {
        overflow-x: auto;
      }
    }
  </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Graduation Store</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="order_history.php">Orders</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="signup.php">Signup</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
  <h2>Your Shopping Cart</h2>

  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead>
        <tr>
          <th>Product</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Total</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $total = 0;
        if (!empty($_SESSION['cart'])) {
          foreach ($_SESSION['cart'] as $product_id => $qty) {
            $result = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
            $product = mysqli_fetch_assoc($result);
            $subtotal = $product['price'] * $qty;
            $total += $subtotal;
            echo "<tr>
                    <td>{$product['name']}</td>
                    <td>{$qty}</td>
                    <td>RM " . number_format($product['price'], 2) . "</td>
                    <td>RM " . number_format($subtotal, 2) . "</td>
                    <td><a href='cart.php?remove=$product_id' class='btn btn-danger btn-sm'>Remove</a></td>
                  </tr>";
          }
        } else {
          echo "<tr><td colspan='5'>Your cart is empty.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <div class="total-section">
    <h4>Total: RM <?php echo number_format($total, 2); ?></h4>
    <?php if ($total > 0): ?>
      <a href="checkout.php" class="btn btn-primary mt-3">Proceed to Checkout</a>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
