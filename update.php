<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "UPDATE tasks SET status='completed' WHERE id=$id";
    $conn->query($sql);
}

header('Location: index.php');
exit;
?>
