<?php
session_start();
include 'config.php';

$product_id = $_GET['id']; 
$query = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
  echo "Product not found!";
  exit();

 

}
$imgPath = "cloud/uploads/";
$fullImagePath = $imgPath . $product['image'];
error_log("Image path: " . $fullImagePath);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $product['name']; ?> - Graduation Store</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
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
  
  <div class="container py-5">
    <h2 class="mb-4"><?php echo $product['name']; ?></h2>
    <div class="row">
      <div class="col-md-6">
      <img src="<?= $imgPath.$product['image']; ?>" alt="<?= $product['name'] ?>" class="img-fluid">
      </div>
      <div class="col-md-6">
        <h4>RM <?php echo number_format($product['price'], 2); ?></h4>
        <p><?php echo $product['description']; ?></p>
        <form action="cart.php" method="POST">
          <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
          <div class="d-flex mb-3">
            <input type="number" name="quantity" value="1" min="1" class="form-control me-2" style="width: 100px;">
            <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <footer class="bg-dark text-white text-center py-3">
    <p>&copy; <?php echo date('Y'); ?> Graduation Store. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
