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
    $product_id = $_POST['product_id'];
    $workshop_id = $_POST['workshop_id'];

    // Удаление продукта
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {
        // Обновление количества продукции в цехе
        $change = -1; // Уменьшаем на 1
        $update_stmt = $conn->prepare("CALL UpdateProductCount(?, ?)");
        $update_stmt->bind_param("ii", $workshop_id, $change);
        $update_stmt->execute();
        echo "<div class='alert alert-success'>Продукт удален успешно!</div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка удаления продукта.</div>";
    }
}

$products = $conn->query("SELECT p.product_id, p.product_name, w.workshop_id, w.workshop_name FROM products p JOIN workshops w ON p.workshop_id = w.workshop_id");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Удалить продукт</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Удалить продукт</h1>
        <form method="POST">
            <div class="form-group">
                <label for="product_id">Выберите продукт</label>
                <select name="product_id" class="form-control" required>
                    <?php while ($product = $products->fetch_assoc()): ?>
                    <option value="<?php echo $product['product_id']; ?>" data-workshop-id="<?php echo $product['workshop_id']; ?>">
                        <?php echo htmlspecialchars($product['product_name']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <input type="hidden" name="workshop_id" id="workshop_id" value="">
            <button type="submit" class="btn btn-danger">Удалить</button>
            <a href="manage_products.php" class="btn btn-secondary">Назад</a>
        </form>
    </div>

    <script>
        const productSelect = document.querySelector('select[name="product_id"]');
        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('workshop_id').value = selectedOption.getAttribute('data-workshop-id');
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>