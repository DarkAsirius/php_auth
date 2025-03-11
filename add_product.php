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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['NAME_PRODUCTION'];
    $workshop_id = $_POST['ID_WORKSHOP'];
    $unit_price = $_POST['COST'];

    // Добавление продукта
    $stmt = $conn->prepare("INSERT INTO production (NAME_PRODUCTION, ID_WORKSHOP, COST) VALUES (?, ?, ?)");
    $stmt->bind_param("sid", $product_name, $workshop_id, $unit_price);
    if ($stmt->execute()) {
        // Обновление количества продукции в цехе
        $change_amount = 1; // Увеличиваем на 1
        $update_stmt = $conn->prepare("CALL UpdateProductCount(?, ?)");
        $update_stmt->bind_param("ii", $workshop_id, $change_amount);
        $update_stmt->execute();
        echo "<div class='alert alert-success'>Продукт добавлен успешно!</div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка добавления продукта.</div>";
    }
}

$workshops = $conn->query("SELECT * FROM workshops");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить продукт</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Добавить продукт</h1>
        <form method="POST">
            <div class="form-group">
                <label for="product_name">Название продукта</label>
                <input type="text" name="product_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="workshop_id">Цех</label>
                <select name="workshop_id" class="form-control" required>
                    <?php while ($workshop = $workshops->fetch_assoc()): ?>
                    <option value="<?php echo $workshop['workshop_id']; ?>"><?php echo htmlspecialchars($workshop['workshop_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="unit_price">Цена за единицу</label>
                <input type="number" name="unit_price" class="form-control" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-primary">Добавить</button>
            <a href="manage_products.php" class="btn btn-secondary">Назад</a>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>