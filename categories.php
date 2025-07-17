<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">

<h2>Categories</h2>
<a href="index.php" class="btn btn-secondary mb-3">Back to Tasks</a>
<a href="add_category.php" class="btn btn-primary mb-3">Add New Category</a>

<table class="table table-bordered">
    <tr>
        <th>ID</th><th>Name</th><th>Actions</th>
    </tr>
    <?php
    $cats = $conn->query("SELECT * FROM categories ORDER BY id DESC");
    while ($row = $cats->fetch_assoc()):
    ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td>
            <a href="edit_category.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="delete_category.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete category?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
