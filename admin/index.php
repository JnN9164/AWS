<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
 <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f2f4f7;
      color: #333;
    }

    .container {
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
      padding: 40px;
      margin-top: 50px;
    }

    h2 {
      font-size: 2.5rem;
font-weight: 600;
      color: #004d99;
      text-align: center;
    }

    .list-group-item {
      border-radius: 10px;
      margin-bottom: 10px;
      background-color: #f1f9ff;
      color: #0056b3;
      font-size: 1.2rem;
      font-weight: 500;
      transition: background-color 0.3s, transform 0.3s;
    }

    .list-group-item:hover {
      background-color: #0069d9;
color: #fff;
      transform: scale(1.05);
    }

    .list-group-item a {
      text-decoration: none;
      color: inherit;
    }

    .alert {
      font-size: 1rem;
      font-weight: 500;
      background-color: #e3f2fd;
color: #1565c0;
    }

    .card {
      margin-top: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      background-color: #eaf2f8;
    }

    .card-header {
      background-color: #004d99;
      color: #fff;
      font-weight: 600;
      border-radius: 12px 12px 0 0;
}

    .card-body {
      padding: 20px;
    }

    .btn-custom {
      background-color: #0056b3;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 10px 20px;
      font-weight: 600;
      transition: background-color 0.3s;
    }

    .btn-custom:hover {
      background-color: #003366;
color: #f8f9fa;
    }

    .btn-logout {
      background-color: #dc3545;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 10px 20px;
      font-weight: 600;
      transition: background-color 0.3s;
    }

    .btn-logout:hover {
      background-color: #c82333;
      color: #f8f9fa;
   }

    .alert-success {
      background-color: #d4edda;
      border-color: #c3e6cb;
      color: #155724;
    }
  </style>
</head>

<body>

  <div class="container">
 <h2>Admin Dashboard</h2>

    <?php if (isset($_SESSION['message'])): ?>
      <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
        <?= $_SESSION['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="card">
      <div class="card-header">
        Quick Access
      </div>
      <div class="card-body">
        <ul class="list-group">
          <li class="list-group-item"><a href="products_list.php">Edit Product</a></li>
<li class="list-group-item"><a href="view_orders.php">View Orders</a></li>
           <li class="list-group-item"><a href="user.php">Customers</a></li>
        </ul>
      </div>
    </div>

    <!-- Logout Button -->
    <div class="text-center mt-4">
      <a href="../logout.php" class="btn btn-logout">Logout</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // 自动关闭提示框（3秒后）
    setTimeout(() => {
      const alertEl = document.querySelector('.alert');
 if (alertEl) {
        const alert = new bootstrap.Alert(alertEl);
        alert.close();
      }
    }, 3000);
  </script>
</body>

</html>
