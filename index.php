<?php include 'config.php'; session_start();
$imgPath = '/cloud/uploads/';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Graduation Store</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
      letter-spacing: 1px;
    }

    .nav-link {
      color: #ecf0f1 !important;
      font-weight: 500;
      letter-spacing: 0.5px;
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
      color: #34495e;
      font-weight: 600;
      margin-bottom: 30px;
    }

    .card {
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    }

    .card-img-top {
      height: 250px;
      object-fit: cover;
      border-radius: 12px 12px 0 0;
    }

    .card-body {
      background-color: #ffffff;
      padding: 25px;
    }

    .card-title {
      font-size: 1.4rem;
      font-weight: 700;
      color: #2c3e50;
    }

    .card-text {
      font-size: 1rem;
      color: #7f8c8d;
      margin-bottom: 15px;
    }

    .price {
      font-size: 1.2rem;
      font-weight: 600;
      color: #27ae60;
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

    footer {
      background-color: #2c3e50;
      color: #ecf0f1;
      padding: 20px;
      text-align: center;
      margin-top: 40px;
      border-radius: 8px 8px 0 0;
    }

    footer p {
      margin: 0;
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
          <li class="nav-item"><a class="nav-link" href="cart.php">Cart <span id="cart-count"><?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ?></span></a></li>
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
    <h2 class="mb-4">Our Products</h2>
    <div class="row">
      <?php
      $result = mysqli_query($conn, "SELECT * FROM products");
      while ($row = mysqli_fetch_assoc($result)) {
        echo "
        <div class='col-md-4 mb-4'>
          <div class='card h-100'>
            <img src='" . $imgPath . "{$row['image']}' class='card-img-top' alt='{$row['name']}'>
            <div class='card-body'>
              <h5 class='card-title'>{$row['name']}</h5>
              <p class='card-text'>{$row['description']}</p>
              <p class='price'>RM {$row['price']}</p>
              <form action='cart.php' method='POST' id='cartForm-{$row['id']}'>
                <input type='hidden' name='product_id' value='{$row['id']}'>
                <div class='d-flex mb-2'>
                  <input type='number' name='quantity' value='1' min='1' class='form-control me-2' style='width: 100px;'>
                  <button type='button' class='btn btn-primary add-to-cart-btn' data-product-id='{$row['id']}'>Add to Cart</button>
                </div>
              </form>
            </div>
          </div>
        </div>";
      }
      ?>
    </div>
  </div>

  <footer>
    <p>&copy; <?php echo date('Y'); ?> Graduation Store. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
      button.addEventListener('click', function() {
        let form = document.getElementById('cartForm-' + this.getAttribute('data-product-id'));
        let formData = new FormData(form);

        fetch('add_to_cart.php', {
          method: 'POST',
          body: formData,
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Update cart count
            document.getElementById('cart-count').textContent = data.cart_count;

            // Show SweetAlert success message
            Swal.fire({
              icon: 'success',
              title: 'Item added to cart!',
              showConfirmButton: false,
              timer: 1500
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Failed to add item to cart',
              text: 'Something went wrong, please try again.',
            });
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while adding the item to the cart.',
          });
        });
      });
    });
  </script>
</body>

</html>
