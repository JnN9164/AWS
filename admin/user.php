<?php include '../config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Role Management</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
<div class="d-flex justify-content-between">
                            <a href="index.php" class="btn btn-secondary">← Back</a>

                        </div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">User Role Management</h2>
 <a href="create_user.php" class="btn btn-primary">➕ Create New User</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Username</th>
                        <th scope="col">Role</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT * FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);

                if ($result && $result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary me-2">Edit</a>
                            <a href="delete_user.php?id=<?= $row['id'] ?>"
                               class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Delete this user?')">Delete</a>
                        </td>
                    </tr>
                      <?php
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No users found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap JS (Optional for interactivity) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
