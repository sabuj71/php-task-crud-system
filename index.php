<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Task Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
<h2>Task Management System</h2>

<div class="mb-3">
    <a href="add_task.php" class="btn btn-primary">Add New Task</a>
    <a href="categories.php" class="btn btn-info">Manage Categories</a>
</div>

<form method="GET" class="mb-3">
    <select name="category_id" class="form-select w-25 d-inline">
        <option value="">Filter by Category</option>
        <?php
        $cats = $conn->query("SELECT * FROM categories");
        while ($c = $cats->fetch_assoc()):
        ?>
        <option value="<?= $c['id'] ?>" <?= (isset($_GET['category_id']) && $_GET['category_id'] == $c['id']) ? 'selected' : '' ?>><?= $c['name'] ?></option>
        <?php endwhile; ?>
    </select>
    <button class="btn btn-secondary">Filter</button>
</form>

<table class="table table-bordered">
<tr>
    <th>Title</th><th>Description</th><th>Due Date</th><th>Category</th><th>Image</th><th>Actions</th>
</tr>
<?php
$filter = isset($_GET['category_id']) && $_GET['category_id'] ? "WHERE t.category_id={$_GET['category_id']}" : "";
$tasks = $conn->query("SELECT t.*, c.name as category FROM tasks t JOIN categories c ON t.category_id = c.id $filter ORDER BY t.id DESC");
while($row = $tasks->fetch_assoc()):
?>
<tr>
    <td><?= htmlspecialchars($row['title']) ?></td>
    <td><?= substr(htmlspecialchars($row['description']), 0, 50) ?>...</td>
    <td><?= $row['due_date'] ?></td>
    <td><?= $row['category'] ?></td>
    <td><?php if($row['image']): ?><img src="uploads/<?= $row['image'] ?>" width="50"><?php endif; ?></td>
    <td>
        <a href="edit_task.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="delete_task.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this task?')" class="btn btn-sm btn-danger">Delete</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
