<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];


    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', 'user')";
    mysqli_query($conn, $query);
    
    echo "<div class='alert alert-success'>User registered successfully!</div>";


}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Signup - Graduation Store</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
        }

        .navbar {
            background-color: #004d99;
        }

        .navbar-brand {
            font-weight: 600;
        }
        
.container {
            background-color: white;
            padding: 40px;
            margin-top: 100px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 450px;
            margin: auto;
        }

        h2 {
            font-size: 2rem;
            font-weight: 600;
            color: #004d99;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 8px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        button {
            background-color: #0069d9;
            border-color: #0062cc;
            border-radius: 8px;
            padding: 10px;
            width: 100%;
            font-weight: 600;
            transition: all 0.3s;
        }

        button:hover {
background-color: #0056b3;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
        }

        .navbar-nav .nav-link:hover {
            color: #d4e1f0;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9rem;
            color: #888;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
            margin-top: 20px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
            margin-top: 20px;
        }

    </style>
</head>
<body>

    <!-- Navbar -->
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

    <!-- Signup Form -->
    <div class="container">
        <h2>Signup</h2>
        <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form action="signup.php" method="POST">
            <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Enter Username" required>
            </div>
<div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2025 Graduation Store. All rights reserved.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
