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
    $contract_number = $_POST['contract_number'];
    $customer_name = $_POST['customer_name'];
    $customer_address = $_POST['customer_address'];
    $contract_date = $_POST['contract_date'];
    $completion_date = $_POST['completion_date'];

    $stmt = $conn->prepare("UPDATE contracts SET customer_name = ?, customer_address = ?, contract_date = ?, completion_date = ? WHERE contract_number = ?");
    $stmt->bind_param("ssssi", $customer_name, $customer_address, $contract_date, $completion_date, $contract_number);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Контракт обновлен успешно!</div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка обновления контракта.</div>";
    }
}

$contract_number = $_GET['number'];
$contract_stmt = $conn->prepare("SELECT * FROM contracts WHERE contract_number = ?");
$contract_stmt->bind_param("i", $contract_number);
$contract_stmt->execute();
$contract = $contract_stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать контракт</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Редактировать контракт</h1>
        <form method="POST">
            <input type="hidden" name="contract_number" value="<?php echo $contract['contract_number']; ?>">
            <div class="form-group">
                <label for="customer_name">Имя клиента</label>
                <input type="text" name="customer_name" class="form-control" value="<?php echo htmlspecialchars($contract['customer_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="customer_address">Адрес клиента</label>
                <input type="text" name="customer_address" class="form-control" value="<?php echo htmlspecialchars($contract['customer_address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="contract_date">Дата контракта</label>
                <input type="date" name="contract_date" class="form-control" value="<?php echo $contract['contract_date']; ?>" required>
            </div>
            <div class="form-group">
                <label for="completion_date">Дата завершения</label>
                <input type="date" name="completion_date" class="form-control" value="<?php echo $contract['completion_date']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="manage_contracts.php" class="btn btn-secondary">Назад</a>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>