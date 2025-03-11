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
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $workshop_id = $_POST['workshop_id'];
    $unit_price = $_POST['unit_price'];

    $stmt = $conn->prepare("UPDATE products SET product_name = ?, workshop_id = ?, unit_price = ? WHERE product_id = ?");
    $stmt->bind_param("sidi", $product_name, $workshop_id, $unit_price, $product_id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Продукт обновлен успешно!</div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка обновления продукта.</div>";
    }
}

$product_id = $_GET['id'];
$product_stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$product_stmt->bind_param("i", $product_id);
$product_stmt->execute();
$product = $product_stmt->get_result()->fetch_assoc();

$workshops = $conn->query("SELECT * FROM workshops");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать продукт</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Редактировать продукт</h1>
        <form method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
            <div class="form-group">
                <label for="product_name">Название продукта</label>
                <input type="text" name="product_name" class="form-control" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="workshop_id">Цех</label>
                <select name="workshop_id" class="form-control" required>
                    <?php while ($workshop = $workshops->fetch_assoc()): ?>
                    <option value="<?php echo $workshop['workshop_id']; ?>" <?php echo $workshop['workshop_id'] == $product['workshop_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($workshop['workshop_name']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="unit_price">Цена за единицу</label>
                <input type="number" name="unit_price" class="form-control" value="<?php echo $product['unit_price']; ?>" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="manage_products.php" class="btn btn-secondary">Назад</a>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>