<?php include 'config.php';
$id = $_GET['id'] ?? null;
$conn->query("DELETE FROM categories WHERE id=$id");
header("Location: categories.php");
?>
