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

$result = $conn->query("SELECT p.ID_PRODUCTION, p.NAME_PRODUCTION, p.ID_WORKSHOP, w.NAME FROM production p JOIN workshops w ON p.ID_WORKSHOP = w.ID_WORKSHOP");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление продукцией</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Список продукции</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название продукта</th>
                    <th>Цех</th>
                    <th>Цена за единицу</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $product['product_id']; ?></td>
                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($product['workshop_name']); ?></td>
                    <td><?php echo htmlspecialchars($product['unit_price']); ?></td>
                    <td>
                        <a href="edit_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-warning">Редактировать</a>
                        <a href="delete_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-danger">Удалить</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <p><a href="add_product.php"class="btn btn-primary">Добавить продукт</a></p>
        <p><a href="dashboard.php" class="btn btn-secondary">Назад</a></p>
    </div>
</body>
</html>

<?php
$conn->close();
?>