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
    $order_id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Заказ удален успешно!</div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка удаления заказа.</div>";
    }
}

echo "<p><a href='manage_orders.php' class='btn btn-secondary'>Назад</a></p>";

$conn->close();
?>