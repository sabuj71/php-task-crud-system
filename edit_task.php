<?php include 'config.php';
$id = $_GET['id'] ?? null;
if (!$id) die('Invalid Task ID');

$task = $conn->query("SELECT * FROM tasks WHERE id=$id")->fetch_assoc();
if (!$task) die('Task not found');

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $due_date = $_POST['due_date'];
    $category_id = $_POST['category_id'];
    $image_name = $task['image'];

    if (!$title || !$description || !$due_date || !$category_id) $errors[] = "All fields are required.";

    if ($_FILES['image']['name']) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) $errors[] = "Only JPG, PNG allowed.";
        if ($_FILES['image']['size'] > 2*1024*1024) $errors[] = "Max 2MB file size.";
        if (!$errors) {
            if ($image_name && file_exists("uploads/".$image_name)) unlink("uploads/".$image_name);
            $image_name = time().'.'.$ext;
            move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$image_name);
        }
    }

    if (!$errors) {
        $stmt = $conn->prepare("UPDATE tasks SET title=?, description=?, due_date=?, category_id=?, image=? WHERE id=?");
        $stmt->bind_param("sssisi", $title, $description, $due_date, $category_id, $image_name, $id);
        $stmt->execute();
        header("Location: index.php");
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Task</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"></head>
<body class="container py-4">

<h2>Edit Task</h2>
<a href="index.php" class="btn btn-secondary mb-3">Back to Tasks</a>

<?php foreach($errors as $e) echo "<div class='alert alert-danger'>$e</div>"; ?>

<form method="POST" enctype="multipart/form-data" class="w-50">
    <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" class="form-control mb-2" required>
    <textarea name="description" class="form-control mb-2" required><?= htmlspecialchars($task['description']) ?></textarea>
    <input type="date" name="due_date" value="<?= $task['due_date'] ?>" class="form-control mb-2" required>
    <select name="category_id" class="form-select mb-2" required>
        <option value="">Select Category</option>
        <?php $cats = $conn->query("SELECT * FROM categories");
        while ($c = $cats->fetch_assoc()): ?>
        <option value="<?= $c['id'] ?>" <?= $c['id']==$task['category_id']?'selected':'' ?>><?= $c['name'] ?></option>
        <?php endwhile; ?>
    </select>
    <?php if($task['image']): ?><img src="uploads/<?= $task['image'] ?>" width="80"><br><?php endif; ?>
    <input type="file" name="image" class="form-control mb-2">
    <button class="btn btn-success">Update Task</button>
</form>
</body>
</html>
