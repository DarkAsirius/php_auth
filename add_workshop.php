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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $workshop_name = $_POST['workshop_name'];
    $workshop_head = $_POST['workshop_head'];
    $workshop_phone = $_POST['workshop_phone'];

    $stmt = $conn->prepare("INSERT INTO workshops (workshop_name, workshop_head, workshop_phone) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $workshop_name, $workshop_head, $workshop_phone);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Цех добавлен успешно!</div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка добавления цеха.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить цех</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Добавить цех</h1>
        <form method="POST">
            <div class="form-group">
                <label for="workshop_name">Название цеха</label>
                <input type="text" name="workshop_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="workshop_head">Руководитель</label>
                <input type="text" name="workshop_head" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="workshop_phone">Телефон</label>
                <input type="text" name="workshop_phone" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Добавить</button>
            <a href="manage_workshops.php" class="btn btn-secondary">Назад</a>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>