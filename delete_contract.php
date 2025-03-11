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

if (isset($_GET['number'])) {
    $contract_number = $_GET['number'];
    $stmt = $conn->prepare("DELETE FROM contracts WHERE contract_number = ?");
    $stmt->bind_param("i", $contract_number);
    if ($stmt->execute()) {
        echo "<div class ='alert alert-success'>Контракт удален успешно!</div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка удаления контракта.</div>";
    }
}

echo "<p><a href='manage_contracts.php' class='btn btn-secondary'>Назад</a></p>";

$conn->close();
?>