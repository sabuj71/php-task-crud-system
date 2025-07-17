<?php include 'config.php';
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $due_date = $_POST['due_date'];
    $category_id = $_POST['category_id'];
    $image_name = null;

    if (!$title || !$description || !$due_date || !$category_id) $errors[] = "All fields are required.";

    if ($_FILES['image']['name']) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) $errors[] = "Only JPG, PNG allowed.";
        if ($_FILES['image']['size'] > 2*1024*1024) $errors[] = "Max 2MB file size.";
        if (!$errors) {
            $image_name = time().'.'.$ext;
            move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$image_name);
        }
    }

    if (!$errors) {
        $stmt = $conn->prepare("INSERT INTO tasks (title, description, due_date, category_id, image, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssds", $title, $description, $due_date, $category_id, $image_name);
        $stmt->execute();
        header("Location: index.php");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Task</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
<h2>Add New Task</h2>
<a href="index.php" class="btn btn-secondary mb-3">Back to Tasks</a>

<?php foreach($errors as $e) echo "<div class='alert alert-danger'>$e</div>"; ?>

<form method="POST" enctype="multipart/form-data" class="w-50">
    <input type="text" name="title" class="form-control mb-2" placeholder="Task Title" required>
    <textarea name="description" class="form-control mb-2" placeholder="Description" required></textarea>
    <input type="date" name="due_date" class="form-control mb-2" required>
    <select name="category_id" class="form-select mb-2" required>
        <option value="">Select Category</option>
        <?php $cats = $conn->query("SELECT * FROM categories");
        while ($c = $cats->fetch_assoc()): ?>
        <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
        <?php endwhile; ?>
    </select>
    <input type="file" name="image" class="form-control mb-2">
    <button class="btn btn-success">Save Task</button>
</form>
</body>
</html>
