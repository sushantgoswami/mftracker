<?php
// Simple Starter Installer Logic
$step = isset($_GET['step']) ? intval($_GET['step']) : 1;
$error = '';

if ($step == 2) {
    $db_host = $_POST['db_host'] ?? 'localhost';
    $db_name = $_POST['db_name'] ?? '';
    $db_user = $_POST['db_user'] ?? '';
    $db_pass = $_POST['db_pass'] ?? '';
    
    // Test database connection
    $conn = @new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($conn->connect_error) {
        $error = "Connection failed: " . $conn->connect_error;
        $step = 1;
    } else {
        // Successfully connected, create a sample config file content
        $config_content = "<?php\ndefine('DB_NAME', '$db_name');\ndefine('DB_USER', '$db_user');\ndefine('DB_PASSWORD', '$db_pass');\ndefine('DB_HOST', '$db_host');\n";
        file_put_contents('config.php', $config_content);
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Custom App Installation</title>
    <style>
        body { font-family: sans-serif; background: #f0f0f1; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .install-box { background: #fff; padding: 30px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.13); width: 400px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; margin: 6px 0 16px 0; border: 1px solid #8c8f94; border-radius: 4px; box-sizing: border-box; }
        .button { background: #2271b1; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; width: 100%; }
        .error { color: #d63638; margin-bottom: 15px; }
    </style>
</head>
<body>
<div class="install-box">
    <?php if ($step == 1): ?>
        <h2>Database Connection</h2>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <form method="post" action="?step=2">
            <label>Database Name</label>
            <input type="text" name="db_name" required>
            <label>Username</label>
            <input type="text" name="db_user" required>
            <label>Password</label>
            <input type="password" name="db_pass">
            <label>Database Host</label>
            <input type="text" name="db_host" value="localhost" required>
            <button type="submit" class="button">Submit & Install</button>
        </form>
    <?php elseif ($step == 2): ?>
        <h2>Success!</h2>
        <p>Configuration file created successfully and database connected.</p>
        <a href="index.php"><button class="button">Log In / Go to App</button></a>
    <?php endif; ?>
</div>
</body>
</html>
