<?php

if( isset( $_POST[ 'Upload' ] ) ) {
	$upload_dir = realpath(DVWA_WEB_PAGE_TO_ROOT . "hackable/uploads") . DIRECTORY_SEPARATOR;

	// 安全地取得檔案資訊
	$file_name = basename($_FILES['uploaded']['name']);
	$file_tmp  = $_FILES['uploaded']['tmp_name'];
	$file_type = mime_content_type($file_tmp);
	$file_ext  = pathinfo($file_name, PATHINFO_EXTENSION);

	// 允許的副檔名和 MIME 類型（可根據需求調整）
	$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
	$allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];

	// 驗證副檔名與 MIME 類型
	if (!in_array(strtolower($file_ext), $allowed_extensions) || !in_array($file_type, $allowed_mime_types)) {
		$html .= '<pre>Only image files (JPG, PNG, GIF) are allowed.</pre>';
	} else {
		$target_path = $upload_dir . $file_name;

		// 檢查目標目錄是否存在
		if (!is_dir($upload_dir)) {
			mkdir($upload_dir, 0755, true);
		}

		// 嘗試移動檔案
		if (!move_uploaded_file($file_tmp, $target_path)) {
			$html .= '<pre>Your image was not uploaded.</pre>';
		} else {
			$safe_name = htmlspecialchars($file_name, ENT_QUOTES, 'UTF-8');
			$html .= "<pre>File '{$safe_name}' uploaded successfully!</pre>";
		}
	}
}
?>
