<?php

if (isset($_POST['Upload'])) {
    $upload_dir = realpath(DVWA_WEB_PAGE_TO_ROOT . "hackable/uploads") . DIRECTORY_SEPARATOR;

    // 安全地建立目錄
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $file_name = basename($_FILES['uploaded']['name']);
    $file_tmp  = $_FILES['uploaded']['tmp_name'];
    $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // 安全檢查：避免目錄穿越 + 特殊字元
    $safe_name = preg_replace('/[^a-zA-Z0-9\._-]/', '_', $file_name);

    // 強制使用唯一檔名（避免覆蓋）
    $final_name = uniqid("img_", true) . '.' . $file_ext;
    $target_path = $upload_dir . $final_name;

    // 限制允許副檔名
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($file_ext, $allowed_extensions)) {
        $html .= '<pre>Only image files (JPG, PNG, GIF) are allowed.</pre>';
        return;
    }

    // 強制檢查圖片內容是否為真圖片
    $image_info = getimagesize($file_tmp);
    if ($image_info === false) {
        $html .= '<pre>Invalid image file. Upload rejected.</pre>';
        return;
    }

    // 最終移動檔案
    if (!move_uploaded_file($file_tmp, $target_path)) {
        $html .= '<pre>Your image was not uploaded.</pre>';
    } else {
        $html .= "<pre>File '" . htmlspecialchars($final_name, ENT_QUOTES, 'UTF-8') . "' uploaded successfully!</pre>";
    }
}
?>
