<?php include 'config.php';
$id = $_GET['id'] ?? null;
$cat = $conn->query("SELECT * FROM categories WHERE id=$id")->fetch_assoc();
if (!$cat) die('Category not found');

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    if (!$name) $error = "Category name is required.";
    else {
        $stmt = $conn->prepare("UPDATE categories SET name=? WHERE id=?");
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        header("Location: categories.php");
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Category</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"></head>
<body class="container py-4">

<h2>Edit Category</h2>
<a href="categories.php" class="btn btn-secondary mb-3">Back</a>

<?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

<form method="POST" class="w-50">
    <input type="text" name="name" class="form-control mb-2" value="<?= htmlspecialchars($cat['name']) ?>" required>
    <button class="btn btn-success">Update Category</button>
</form>

</body>
</html>
