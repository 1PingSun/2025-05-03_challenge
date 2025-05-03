<?php

if (isset($_POST['Submit'])) {
    // 僅從 POST 拿資料
    $target = $_POST['ip'];

    // 驗證 IP 或網域名稱
    if (filter_var($target, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) || preg_match('/^([a-zA-Z0-9.-]+\.[a-zA-Z]{2,})$/', $target)) {
        
        // 決定 OS 並安全執行 ping
        if (stristr(php_uname('s'), 'Windows NT')) {
            // Windows
            $cmd = shell_exec('ping ' . escapeshellarg($target));
        } else {
            // Unix/Linux
            $cmd = shell_exec('ping -c 4 ' . escapeshellarg($target));
        }

        // 安全顯示結果
        echo "<pre>" . htmlspecialchars($cmd, ENT_QUOTES, 'UTF-8') . "</pre>";
    } else {
        echo "<p>❌ 無效的 IP 或網域名稱。</p>";
    }
}
?>