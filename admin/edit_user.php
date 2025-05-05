<?php include '../config.php'; ?>

<?php
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET username = ?, role = ?, password = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $role, $hashedPassword, $id);
       } else {
        $stmt = $conn->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssi", $username, $role, $id);
    }

    $stmt->execute();
    header("Location: user.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Edit User</h4>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control"
                                   value="<?= htmlspecialchars($user['username']) ?>" required>
 </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password
                                <small class="text-muted">(Leave blank to keep current)</small>
                            </label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••">
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="user.php" class="btn btn-secondary">← Back</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
 </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (optional for interactivity) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
