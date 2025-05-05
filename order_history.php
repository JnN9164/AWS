<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Order History - Graduation Store</title>
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
        <li class="nav-item"><a class="nav-link" href="order_history.php">Orders</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>
  
<div class="container">
  <h2>Your Order History</h2>

  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Total Price</th>
          <th>Date</th>
          <th>Details</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (mysqli_num_rows($result) > 0):
          while ($order = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?php echo $order['id']; ?></td>
              <td>RM <?php echo number_format($order['total_price'], 2); ?></td>
              <td><?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?></td>
              <td><a href="order_details.php?order_id=<?php echo $order['id']; ?>" class="btn btn-info btn-sm">View</a>
              </td>
            </tr>
          <?php endwhile;
        else: ?>
          <tr><td colspan="4">No orders found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php include 'footer.php'?>
</html>
