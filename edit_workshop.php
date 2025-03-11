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
    $workshop_id = $_POST['workshop_id'];
    $workshop_name = $_POST['workshop_name'];
    $workshop_head = $_POST['workshop_head'];
    $workshop_phone = $_POST['workshop_phone'];

    $stmt = $conn->prepare("UPDATE workshops SET workshop_name = ?, workshop_head = ?, workshop_phone = ? WHERE workshop_id = ?");
    $stmt->bind_param("sssi", $workshop_name, $workshop_head, $workshop_phone, $workshop_id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Цех обновлен успешно!</div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка обновления цеха.</div>";
    }
}

$workshop_id = $_GET['id'];
$workshop_stmt = $conn->prepare("SELECT * FROM workshops WHERE workshop_id = ?");
$workshop_stmt->bind_param("i", $workshop_id);
$workshop_stmt->execute();
$workshop = $workshop_stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать цех</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Редактировать цех</h1>
        <form method="POST">
            <input type="hidden" name="workshop_id" value="<?php echo $workshop['workshop_id']; ?>">
            <div class="form-group">
                <label for="workshop_name">Название цеха</label>
                <input type="text" name="workshop_name" class="form-control" value="<?php echo htmlspecialchars($workshop['workshop_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="workshop_head">Руководитель</label>
                <input type="text" name="workshop_head" class="form-control" value="<?php echo htmlspecialchars($workshop['workshop_head']); ?>" required>
            </div>
            <div class="form-group">
                <label for="workshop_phone">Телефон</label>
                <input type="text" ```php
name="workshop_phone" class="form-control" value="<?php echo htmlspecialchars($workshop['workshop_phone']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="manage_workshops.php" class="btn btn-secondary">Назад</a>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>