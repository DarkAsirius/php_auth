<?php
session_start();

$host = 'localhost';
$db = 'typography';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Функции для работы с пользователями
function registerUser  ($conn, $email, $password) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashedPassword);
    return $stmt->execute();
}

function loginUser  ($conn, $email, $password) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['visits'] = $user['visits'] + 1;
            $_SESSION['last_visit'] = $user['last_visit'];
            $stmt = $conn->prepare("UPDATE users SET visits = ?, last_visit = NOW() WHERE id = ?");
            $stmt->bind_param("ii", $_SESSION['visits'], $user['id']);
            $stmt->execute();
            return true;
        }
    }
    return false;
}

// Обработка форм
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (registerUser ($conn, $email, $password)) {
        echo "<div class='alert alert-success'>Регистрация прошла успешно!</div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка регистрации.</div>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (loginUser ($conn, $email, $password)) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Неверный логин или пароль.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Добро пожаловать в Tipografia</h1>

        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2>Регистрация</h2>
                <form method="POST">
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Пароль" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary btn-block">Зарегистрироваться</button>
                </form>

                <h2 class="mt-4">Авторизация</h2>
                <form method="POST">
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Пароль" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-success btn-block">Войти</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>