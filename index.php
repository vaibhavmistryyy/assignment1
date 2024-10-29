<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Load users from a JSON file
    $users = json_decode(file_get_contents('data/users.json'), true);
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Simple authentication check
    foreach ($users as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            $_SESSION['user'] = $user;
            setcookie("user", $username, time() + (86400 * 30), "/");
            header('Location: dashboard.php');
            exit;
        }
    }
    echo "Invalid credentials!";
}
?>

<form method="POST">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Login">
</form>
