<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$host = 'localhost';
$db = 'typography';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM workshops");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление цехами</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Список цехов</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название цеха</th>
                    <th>Руководитель</th>
                    <th>Телефон</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($workshop = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $workshop['workshop_id']; ?></td>
                    <td><?php echo htmlspecialchars($workshop['workshop_name']); ?></td>
                    <td><?php echo htmlspecialchars($workshop['workshop_head']); ?></td>
                    <td><?php echo htmlspecialchars($workshop['workshop_phone']); ?></td>
                    <td>
                        <a href="edit_workshop.php?id=<?php echo $workshop['workshop_id']; ?>" class="btn btn-warning">Редактировать</a>
                        <a href="delete_workshop.php?id=<?php echo $workshop['workshop_id']; ?>" class="btn btn-danger">Удалить</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <p><a href="add_workshop.php"class="btn btn-primary">Добавить цех</a></p>
        <p><a href="dashboard.php" class="btn btn-secondary">Назад</a></p>
    </div>
</body>
</html>

<?php
$conn->close();
?>