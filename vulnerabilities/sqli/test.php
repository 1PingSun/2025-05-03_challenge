<?php
$host = "192.168.0.7";
$database = "dvwa"; // 🔧 確保你有設定正確資料庫名稱
$username = "dvwa";
$password = "password";

try {
    // 使用 SQL Server 的 PDO DSN 格式
    $dsn = "sqlsrv:Server=$host;Database=$database";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];

    $pdo = new PDO($dsn, $username, $password, $options);

    $query = "SELECT first_name FROM users"; // ❌ 不應顯示 password
    $stmt = $pdo->query($query);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo htmlspecialchars($row["first_name"], ENT_QUOTES, 'UTF-8') . "<br />";
    }

} catch (PDOException $e) {
    echo "<p>Database connection failed.</p>";
    // 若為開發環境可顯示錯誤訊息：
    // echo "<pre>" . $e->getMessage() . "</pre>";
}
?>
