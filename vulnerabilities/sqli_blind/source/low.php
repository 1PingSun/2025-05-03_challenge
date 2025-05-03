<?php

if (isset($_GET['Submit'])) {
	$id = $_GET['id'];

	// 驗證 ID 是數字
	if (!ctype_digit($id)) {
		header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
		$html .= '<pre>Invalid User ID.</pre>';
		exit;
	}

	$exists = false;

	switch ($_DVWA['SQLI_DB']) {
		case MYSQL:
			$query = "SELECT first_name, last_name FROM users WHERE user_id = ?";
			$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
			mysqli_stmt_bind_param($stmt, "i", $id);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);

			if ($result && mysqli_num_rows($result) > 0) {
				$exists = true;
			}

			mysqli_stmt_close($stmt);
			mysqli_close($GLOBALS["___mysqli_ston"]);
			break;

		case SQLITE:
			global $sqlite_db_connection;

			$stmt = $sqlite_db_connection->prepare("SELECT first_name, last_name FROM users WHERE user_id = :id");
			$stmt->bindValue(":id", $id, SQLITE3_INTEGER);
			$results = $stmt->execute();

			if ($results && $results->fetchArray()) {
				$exists = true;
			}
			break;
	}

	// 回應用戶
	if ($exists) {
		$html .= '<pre>User ID exists in the database.</pre>';
	} else {
		header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
		$html .= '<pre>User ID is MISSING from the database.</pre>';
	}
}
?>
