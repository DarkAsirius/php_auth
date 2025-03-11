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
    $customer_name = $_POST['customer_name'];
    $customer_address = $_POST['customer_address'];
    $contract_date = $_POST['contract_date'];
    $completion_date = $_POST['completion_date'];

    $stmt = $conn->prepare("INSERT INTO contracts (customer_name, customer_address, contract_date, completion_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $customer_name, $customer_address, $contract_date, $completion_date);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Контракт добавлен успешно!</div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка добавления контракта.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить контракт</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Добавить контракт</h1>
        <form method="POST">
            <div class="form-group">
                <label for="customer_name">Имя клиента</label>
                <input type="text" name="customer_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="customer_address">Адрес клиента</label>
                <input type="text" name="customer_address" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="contract_date">Дата контракта</label>
                <input type="date" name="contract_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="completion_date">Дата завершения</label>
                <input type="date" name="completion _date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Добавить</button>
            <a href="manage_contracts.php" class="btn btn-secondary">Назад</a>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>