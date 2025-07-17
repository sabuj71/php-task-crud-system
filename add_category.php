<?php include 'config.php';
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    if (!$name) $error = "Category name is required.";
    else {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        header("Location: categories.php");
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Add Category</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"></head>
<body class="container py-4">
<h2>Add Category</h2>
<a href="categories.php" class="btn btn-secondary mb-3">Back</a>

<?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

<form method="POST" class="w-50">
    <input type="text" name="name" class="form-control mb-2" placeholder="Category Name" required>
    <button class="btn btn-success">Add Category</button>
</form>
</body>
</html>
