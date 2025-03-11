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
    $contract_number = $_POST['ID_CONTRACT'];
    $product_id = $_POST['ID_PRODUCTION'];
    $quantity = $_POST['COST'];

    $stmt = $conn->prepare("INSERT INTO orders (ID_CONTRACT, ID_PRODUCTION, COST) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $contract_number, $product_id, $quantity);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Заказ добавлен успешно!</div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка добавления заказа.</div>";
    }
}

$contracts = $conn->query("SELECT * FROM contracts");
$products = $conn->query("SELECT * FROM production");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить заказ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Добавить заказ</h1>
        <form method="POST">
            <div class="form-group">
                <label for="contract_number">Договор</label>
                <select name="contract_number" class="form-control" required>
                    <?php while ($contract = $contracts->fetch_assoc ()): ?>
                    <option value="<?php echo $contract['ID_CONTRACT']; ?>"><?php echo htmlspecialchars($contract['customer_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="product_id">Продукт</label>
                <select name="product_id" class="form-control" required>
                    <?php while ($product = $products->fetch_assoc()): ?>
                    <option value="<?php echo $product['ID_PRODUCTION']; ?>"><?php echo htmlspecialchars($product['product_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Количество</label>
                <input type="number" name="quantity" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Добавить</button>
            <a href="manage_orders.php" class="btn btn-secondary">Назад</a>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>