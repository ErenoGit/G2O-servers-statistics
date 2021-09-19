<?php
header("Content-Type: text/html; charset=UTF-8");
session_start();

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(isset($_POST['login'])) {

			$pass = $_POST['pass'] ?? null;
			$admin_hash = require_once 'pass.php';

			$admin_hash = array_key_exists('pass', $admin_hash) ? $admin_hash['pass'] : null;
			if (!empty($pass) && password_verify($pass, $admin_hash)) {
				$_SESSION['admin'] = true;
				echo '<p>Poprawnie zalogowano.</p>';
			} else {
				echo '<p>Hasło nie pasuje!</p>';
			}
		} elseif (isset($_POST['logout'])) {
			$_SESSION['admin'] = false;
			session_regenerate_id();
		}
	}

$is_admin = $_SESSION['admin'] ?? false;
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8"/>
    <title>Podaj dane logowania</title>
</head>
<body>
    <main>
        <?php if (!$is_admin): ?>
        <p>Aby kontynuować musisz podać hasło:</p>
        <form action="" method="POST">
            <input name="pass" type="password" placeholder="Podaj hasło"/>
            <button name="login" type="submit">Zaloguj się</button>
        </form>
    <?php else: ?>
		<a href="index.php">Edytor listy wykresów</a><br /><br />
        <form action="" method="POST">
            <button name="logout" type="submit">Wyloguj się</button>
        </form>
    <?php endif; ?>
    </main>
	
	<br /><a href="../index.php">Powrót do strony</a>
</body>
</html>