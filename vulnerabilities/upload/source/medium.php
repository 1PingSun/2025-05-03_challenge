<?php

if (isset($_POST['Upload'])) {
	$upload_dir = realpath(DVWA_WEB_PAGE_TO_ROOT . "hackable/uploads") . DIRECTORY_SEPARATOR;

	// 安全取得上傳檔案資訊
	$file_tmp  = $_FILES['uploaded']['tmp_name'];
	$file_name = basename($_FILES['uploaded']['name']);
	$file_size = $_FILES['uploaded']['size'];
	$file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
	$file_info = getimagesize($file_tmp); // 更安全的方式來驗證圖片

	// 檢查是否為有效圖片
	if ($file_info !== false && in_array($file_info['mime'], ['image/jpeg', 'image/png']) && $file_size < 100000) {
		// 避免目錄穿越與非法檔名
		$safe_file_name = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $file_name);
		$target_path = $upload_dir . $safe_file_name;

		if (!move_uploaded_file($file_tmp, $target_path)) {
			$html .= '<pre>Your image was not uploaded.</pre>';
		} else {
			$html .= "<pre>" . htmlspecialchars($safe_file_name, ENT_QUOTES, 'UTF-8') . " successfully uploaded!</pre>";
		}
	} else {
		$html .= '<pre>Your image was not uploaded. Only JPEG or PNG images under 100KB are allowed.</pre>';
	}
}
?>
