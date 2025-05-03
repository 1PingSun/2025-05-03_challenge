<?php

if (isset($_POST['Submit'])) {
    // 僅允許 IP v4 位址格式（例如：192.168.1.1）
    $target = $_POST['ip'];

    if (filter_var($target, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        // 根據作業系統組合安全指令
        if (stristr(php_uname('s'), 'Windows NT')) {
            // Windows
            $cmd = escapeshellcmd("ping $target");
        } else {
            // Unix / Linux
            $cmd = escapeshellcmd("ping -c 4 $target");
        }

        // 執行命令
        $output = shell_exec($cmd);

        // 安全輸出結果
        echo "<pre>" . htmlspecialchars($output, ENT_QUOTES, 'UTF-8') . "</pre>";
    } else {
        echo "<p>❌ 請輸入正確的 IPv4 位址！</p>";
    }
}

?>

