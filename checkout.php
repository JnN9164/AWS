<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
//require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];
$total = 0;

$host = 'graduation.cp8dkm9ksdvu.us-east-1.rds.amazonaws.com';
$user = 'admin';
$pass = 'nbuser123';
$db = 'graduation_store';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

foreach ($cart as $product_id => $qty) {
  $result = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
  $product = mysqli_fetch_assoc($result);
  $total += $product['price'] * $qty;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['place_order'])) {
    header('Content-Type: application/json');
    $payment_method = htmlspecialchars($_POST['payment_method'] ?? '');

     if ($payment_method === 'Cash' || $payment_method === 'E-Wallet' || $payment_method === 'Card') {

        $escaped_payment_method = mysqli_real_escape_string($conn, $payment_method);
        mysqli_query($conn, "INSERT INTO orders (user_id, total_price, payment_method) VALUES ($user_id, $total, '$escaped_payment_method')");
        //mysqli_query($conn, "INSERT INTO orders (user_id, total_price, payment_method, total, status) VALUES ($user_id, $total, '$payment_method')");

        $order_id = mysqli_insert_id($conn);

        mysqli_query($conn, "UPDATE orders SET status = 'paid' WHERE id = $order_id");

        foreach ($cart as $product_id => $qty) {
            $result = mysqli_query($conn, "SELECT price FROM products WHERE id = $product_id");
            $price = mysqli_fetch_assoc($result)['price'];
            mysqli_query($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, $product_id, $qty, $price)");
        }

        unset($_SESSION['cart']);

        if ($payment_method === 'Cash') {
            echo json_encode([
                'status' => 'success',
                'payment_status' => 'Payment confirmed via Cash.',
                'qr_code_url' => null,
                'message' => 'Payment successful! You will be redirected to your order shortly.'
            ]);
        } elseif ($payment_method === 'E-Wallet') {
            echo json_encode([
                'status' => 'success',
                'payment_status' => 'Payment confirmed via E-Wallet. Please scan the QR code.',
                'qr_code_url' => 'https://api.qrserver.com/v1/create-qr-code/?data=E-WalletPayment&size=150x150',
                'message' => 'Please scan the QR code.'
            ]);
        } elseif ($payment_method === 'Card') {
            echo json_encode([
                'status' => 'success',
                'payment_status' => 'Payment confirmed via Card. Please provide your card details.',

                                'qr_code_url' => null,
                'message' => 'Please fill in your card details.'
            ]);
        }

    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Please select a valid payment method.'
        ]);
    }
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout & Payment - Graduation Store</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background-color: #f9f9f9;
    }
    .container {
      margin-top: 60px;
      background: #fff;
      padding: 40px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
      border-radius: 10px;
    }
    h2 {
      margin-bottom: 30px;
    }
    .btn-primary {
      width: 100%;
      padding: 10px;
    }
    .payment-success {
      font-size: 1.25rem;
      color: green;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Checkout & Payment</h2>

  <form id="paymentForm">
    <div class="mb-3">
      <label for="payment_method" class="form-label">Select Payment Method:</label>
      <select name="payment_method" id="payment_method" class="form-select" required>
        <option value="">-- Please Choose --</option>
        <option value="Cash">Cash</option>
        <option value="E-Wallet">E-Wallet</option>
        <option value="Card">Card Payment</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Complete Payment</button>
  </form>

  <div id="paymentResult" class="mt-4"></div>

</div>

<script>
document.getElementById('paymentForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);
    const paymentMethod = document.getElementById('payment_method').value;

    Swal.fire({
        title: 'Processing Payment...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
   fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();

        if (data.status === 'success') {
            let resultHtml = '<div class="alert alert-info">';
            resultHtml += '<p><strong>Payment Status:</strong> ' + data.payment_status + '</p>';

            if (paymentMethod === 'E-Wallet') {
                resultHtml += '<div><p>Scan the QR code below to complete the payment:</p>';
                resultHtml += '<img src="' + data.qr_code_url + '" alt="QR Code">';
                resultHtml += '<div class="payment-success mt-3">' + data.message + '</div>';
                resultHtml += '</div>';
            } else if (paymentMethod === 'Card') {
                resultHtml += `
                    <form id="cardPaymentForm">
                        <div class="mb-3">
                            <label for="card_number" class="form-label">Card Number:</label>
                            <input type="text" class="form-control" id="card_number" required placeholder="Card Number">
                        </div>
                        <div class="mb-3">
                            <label for="expiry_date" class="form-label">Expiry Date (MM/YY):</label>
                            <input type="text" class="form-control" id="expiry_date" required placeholder="MM/YY">
                        </div>
                        <div class="mb-3">
                            <label for="cvv" class="form-label">CVV:</label>
                            <input type="text" class="form-control" id="cvv" required placeholder="CVV">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Payment</button>
                    </form>
                `;
            } else {
                resultHtml += '<div class="payment-success">' + data.message + '</div>';

    setTimeout(function() {
        Swal.fire({
            icon: 'success',
            title: 'Payment Successful!',
            text: 'You will be redirected shortly.',
            showConfirmButton: false,
            timer: 3000
        }).then(function() {
            window.location.href = "order_history.php";  // Redirect after payment
        });
    }, 100); // Optionally wait 3 seconds before showing success
            }

            resultHtml += '</div>';
            document.getElementById('paymentResult').innerHTML = resultHtml;

            const cardPaymentForm = document.getElementById('cardPaymentForm');
            if (cardPaymentForm) {
                cardPaymentForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    Swal.fire({
                        icon: 'success',
                        title: 'Payment Successful!',
                        text: 'Your payment has been completed successfully.',
                        showConfirmButton: false,
                        timer: 3000
                    }).then(function() {
                        window.location.href = "order_history.php";  // Redirect to order history after payment
                    });
                });
            }

          if (paymentMethod === 'E-Wallet') {
                setTimeout(function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Payment Successful!',
                        text: 'You will be redirected shortly.',
                        showConfirmButton: false,
                        timer: 3000
                    }).then(function() {
                        window.location.href = "order_history.php";  // Redirect to order history after payment
                    });
                }, 5000);
            }

        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Something went wrong.', 'error');
    });
});
</script>

</body>
</html>
