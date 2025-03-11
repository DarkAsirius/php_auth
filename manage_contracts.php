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

$result = $conn->query("SELECT * FROM contracts");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление контрактами</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Список контрактов</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Номер контракта</th>
                    <th>Имя клиента</th>
                    <th>Адрес клиента</th>
                    <th>Дата контракта</th>
                    <th>Дата завершения</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($contract = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $contract['contract_number']; ?></td>
                    <td><?php echo htmlspecialchars($contract['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($contract['customer_address']); ?></td>
                    <td><?php echo htmlspecialchars($contract['contract_date']); ?></td>
                    <td><?php echo htmlspecialchars($contract['completion_date']); ?></td>
                    <td>
                        <a href="edit_contract.php?number=<?php echo $contract['contract_number']; ?>" class="btn btn-warning">Редактировать</a>
                        <a href="delete_contract.php?number=<?php echo $contract['contract_number']; ?>" class="btn btn-danger">Удалить</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <p><a href="add_contract.php"class="btn btn-primary">Добавить контракт</a></p>
        <p><a href="dashboard.php" class="btn btn-secondary">Назад</a></p>
    </div>
</body>
</html>

<?php
$conn->close();
?>