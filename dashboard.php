<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель управления</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Добро пожаловать на панель управления</h1>
        <p>Вы вошли как: <?php echo htmlspecialchars($_SESSION['role']); ?></p>
        <p><a href="logout.php" class="btn btn-danger">Выйти</a></p>

        <?php if ($role === 'admin'): ?>
            <h2>Управление пользователями</h2>
            <p><a href="manage_users.php" class="btn btn-primary">Управление пользователями</a></p>
            <h2>Управление продуктами</h2>
            <p><a href="manage_products.php" class="btn btn-primary">Управление продуктами</a></p>
            <h2>Управление контрактами</h2>
            <p><a href="manage_contracts.php" class="btn btn-primary">Управление контрактами</a></p>
            <h2>Управление заказами</h2>
            <p><a href="manage_orders.php" class="btn btn-primary">Управление заказами</a></p>
        <?php endif; ?>

        <?php if ($role === 'operator' || $role === 'admin'): ?>
            <h2>Формирование ведомости</h2>
            <p><a href="attendance_report.php" class="btn btn-primary">Сформировать ведомость</a></p>
        <?php endif; ?>
    </div>
</body>
</html>