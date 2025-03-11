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
    $order_id = $_POST['order_id'];
    $contract_number = $_POST['contract_number'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("UPDATE orders SET contract_number = ?, product_id = ?, quantity = ? WHERE order_id = ?");
    $stmt->bind_param("iiii", $contract_number, $product_id, $quantity, $order_id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Заказ обновлен успешно!</div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка обновления заказа.</div>";
    }
}

$order_id = $_GET['id'];
$order_stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order = $order_stmt->get_result()->fetch_assoc();

$contracts = $conn->query("SELECT * FROM contracts");
$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать заказ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Редактировать заказ</h1>
        <form method="POST">
            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
            <div class="form-group">
                <label for="contract_number">Договор</label>
                <select name="contract_number" class="form-control" required>
                    <?php while ($contract = $contracts->fetch_assoc()): ?>
                    <option value="<?php echo $contract['contract_number']; ?>" <?php echo $contract['contract_number'] == $order['contract_number'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($contract['customer_name']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="product_id">Продукт</label>
                <select name="product_id" class="form-control" required>
                    <?php while ($product = $products->fetch_assoc()): ?>
                    <option value="<?php echo $product['product_id']; ?>" <?php echo $product['product_id'] == $order['product_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($product['product_name']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Количество</label>
                <input type="number" name="quantity" class="form-control" value="<?php echo $order['quantity']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Сохран ```php
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="manage_orders.php" class="btn btn-secondary">Назад</a>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>