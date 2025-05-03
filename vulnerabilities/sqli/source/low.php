<?php

if (isset($_GET['Submit'])) {
    $id = $_GET['id'];

    // ✅ 輸入驗證：僅接受數字 ID
    if (!ctype_digit($id)) {
        $html .= "<pre>Invalid User ID. Must be a number.</pre>";
        return;
    }

    switch ($_DVWA['SQLI_DB']) {
        case MYSQL:
            $query = "SELECT first_name, last_name FROM users WHERE user_id = ?";
            $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                $first = htmlspecialchars($row["first_name"], ENT_QUOTES, 'UTF-8');
                $last  = htmlspecialchars($row["last_name"], ENT_QUOTES, 'UTF-8');
                $safeId = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');

                $html .= "<pre>ID: {$safeId}<br />First name: {$first}<br />Surname: {$last}</pre>";
            }

            mysqli_stmt_close($stmt);
            mysqli_close($GLOBALS["___mysqli_ston"]);
            break;

        case SQLITE:
            global $sqlite_db_connection;

            $stmt = $sqlite_db_connection->prepare("SELECT first_name, last_name FROM users WHERE user_id = :id");
            $stmt->bindValue(":id", $id, SQLITE3_INTEGER);
            $results = $stmt->execute();

            while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
                $first = htmlspecialchars($row["first_name"], ENT_QUOTES, 'UTF-8');
                $last  = htmlspecialchars($row["last_name"], ENT_QUOTES, 'UTF-8');
                $safeId = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');

                $html .= "<pre>ID: {$safeId}<br />First name: {$first}<br />Surname: {$last}</pre>";
            }
            break;
    }
}
?>
