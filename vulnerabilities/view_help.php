<?php

define('DVWA_WEB_PAGE_TO_ROOT', '../');
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup(['authenticated']);

$page = dvwaPageNewGrab();
$page['title'] = 'Help' . $page['title_separator'] . $page['title'];

// ✅ 定義允許的模組與語系
$allowedModules = [
	'brute', 'csrf', 'exec', 'fi', 'upload',
	'captcha', 'sqli', 'sqli_blind', 'weak_id',
	'xss_d', 'xss_r', 'xss_s', 'csp', 'javascript',
	'authbypass', 'open_redirect', 'encryption', 'api'
];

$allowedLocales = ['en', 'zh']; // 如有更多語系請補上

if (isset($_GET['id'], $_GET['security'], $_GET['locale'])) {
	$id = $_GET['id'];
	$security = $_GET['security'];
	$locale = $_GET['locale'];

	// ✅ 白名單過濾模組與語系
	if (in_array($id, $allowedModules) && in_array($locale, $allowedLocales)) {
		$basePath = DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/{$id}/help/";
		$filename = $basePath . ($locale === 'en' ? 'help.php' : "help.{$locale}.php");

		if (file_exists($filename)) {
			ob_start();
			include($filename);
			$help = ob_get_clean();
		} else {
			$help = "<p>Help file not found.</p>";
		}
	} else {
		$help = "<p>Invalid module or locale specified.</p>";
	}
} else {
	$help = "<p>Missing required parameters.</p>";
}

// 加入樣式與腳本
$page['body'] .= "
<script src='/vulnerabilities/help.js'></script>
<link rel='stylesheet' type='text/css' href='/vulnerabilities/help.css' />

<div class=\"body_padded\">
	{$help}
</div>\n";

dvwaHelpHtmlEcho($page);
?>
