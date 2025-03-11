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
    $id = $_POST['id'];
    $role = $_POST['role'];
    $stmt = $conn ->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $role, $id);
    $stmt->execute();
    header("Location: manage_users.php");
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование пользователя</title>
</head>
<body>
    <h1>Редактирование пользователя</h1>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        <label for="role">Роль:</label>
        <select name="role" id="role">
            <option value="guest" <?php echo $user['role'] === 'guest' ? 'selected' : ''; ?>>Гость</option>
            <option value="operator" <?php echo $user['role'] === 'operator' ? 'selected' : ''; ?>>Оператор</option>
            <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Администратор</option>
        </select>
        <button type="submit">Сохранить</button>
    </form>
    <p><a href="manage_users.php">Назад</a></p>
</body>
</html>

<?php
$conn->close();
?>