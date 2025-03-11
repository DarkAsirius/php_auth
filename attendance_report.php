<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'operator' && $_SESSION['role'] !== 'admin')) {
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

// Получение данных о операторах
$result = $conn->query("SELECT * FROM users WHERE role = 'operator'");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Отчет о посещаемости</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Отчет о посещаемости операторов</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Количество посещений</th>
                    <th>Последний визит</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($operator = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $operator['id']; ?></td>
                    <td><?php echo htmlspecialchars($operator['email']); ?></td>
                    <td><?php echo $operator['visits']; ?></td>
                    <td><?php echo $operator['last_visit'] ? htmlspecialchars($operator['last_visit']) : 'Не было визитов'; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <p><a href="dashboard.php" class="btn btn-secondary">Назад</a></p>
    </div>
</body>
</html>

<?php
$conn->close();
?>