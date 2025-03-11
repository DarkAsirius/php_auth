<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$host = 'localhost';
$db = 'typography';
$user = 'root';
$pass = 'RayKub335248';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $workshop_id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM workshops WHERE workshop_id = ?");
    $stmt->bind_param("i", $workshop_id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Цех удален успешно!</div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка удаления цеха.</div>";
    }
}

echo "<p><a href='manage_workshops.php' class='btn btn-secondary'>Назад</a></p>";

$conn->close();
?>