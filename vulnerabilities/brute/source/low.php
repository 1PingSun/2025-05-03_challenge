<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Login'])) {
    $user = trim($_POST['username']);
    $pass = $_POST['password'];

    // 安全雜湊處理（假設資料庫已使用 password_hash 儲存）
    $db = $GLOBALS["___mysqli_ston"];

    // 使用 prepared statement
    $stmt = mysqli_prepare($db, "SELECT user, password, avatar FROM users WHERE user = ?");
    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $db_hash = $row['password'];
        $avatar = $row['avatar'];

        if (password_verify($pass, $db_hash)) {
            // Login success
            $safeUser = htmlspecialchars($user, ENT_QUOTES, 'UTF-8');
            $safeAvatar = htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8');

            $html .= "<p>Welcome to the password protected area {$safeUser}</p>";
            $html .= "<img src=\"{$safeAvatar}\" alt=\"User Avatar\" />";
        } else {
            $html .= "<pre><br />Username and/or password incorrect.</pre>";
        }
    } else {
        $html .= "<pre><br />Username and/or password incorrect.</pre>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($db);
}
?>
