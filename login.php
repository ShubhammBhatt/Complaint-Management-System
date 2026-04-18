<?php
session_start();
if (isset($_POST['login'])) {
    if ($_POST['user'] == "admin" && $_POST['pass'] == "admin123") {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
    } else { $error = "Invalid Login!"; }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body { font-family: sans-serif; background: #764ba2; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-card { background: white; padding: 30px; border-radius: 10px; width: 300px; text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; padding: 10px; background: #667eea; color: white; border: none; cursor: pointer; border-radius: 5px; }
    </style>
</head>
<body>
<div class="login-card">
    <h2>Admin Login</h2>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="user" placeholder="Username" required>
        <input type="password" name="pass" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <br><a href="index.php" style="font-size: 12px; color: #888;">Back to Home</a>
</div>
</body>
</html>
