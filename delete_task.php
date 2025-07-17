<?php include 'config.php';
$id = $_GET['id'] ?? null;
if ($id) {
    $task = $conn->query("SELECT image FROM tasks WHERE id=$id")->fetch_assoc();
    if ($task && $task['image'] && file_exists("uploads/".$task['image'])) {
        unlink("uploads/".$task['image']);
    }
    $conn->query("DELETE FROM tasks WHERE id=$id");
}
header("Location: index.php");
?>
