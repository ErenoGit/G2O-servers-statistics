<?php
header("Content-Type: text/html; charset=UTF-8");
session_start();
$is_admin = $_SESSION['admin'] ?? false;
?>

<?php if ($is_admin): ?>

<b>Aktualna lista:</b><br /><br />
<?php
	function deleteLineFromTrackedServers($id)
	{
		$file_out = file('../trackedServers.txt');

		file_put_contents('test1.txt', implode("", $file_out));

		unset($file_out[$id]);
		
		end($file_out);
		$lastElement = key($file_out);
		reset($file_out);
		$lastElementText = $file_out[$lastElement];
		$lastElementText = str_replace(PHP_EOL, '', $lastElementText); 
		$file_out[$lastElement] = $lastElementText;
		
		file_put_contents('../trackedServers.txt', implode("", $file_out));
		
		header("Location: index.php");
	}
	
	function addLineToTrackedServers($ipAndPort, $name)
	{
		$file = '../trackedServers.txt';
		file_put_contents($file, PHP_EOL.$ipAndPort.' '.$name, FILE_APPEND | LOCK_EX);
		
		header("Location: index.php");
	}
	
	

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		if(isset($_GET['usun'])) 
		{
			$idToDelete = $_GET['usun'];
			deleteLineFromTrackedServers($idToDelete);
		}
		else if(isset($_GET['dodaj'])) 
		{
			$ipAndPort = $_GET['ip'];
			$name = $_GET['nazwa'];
			addLineToTrackedServers($ipAndPort, $name);
		}
	}




	$fh = fopen('../trackedServers.txt','r');
	$i = 0;
	while ($line = fgets($fh)) 
	{
		$array = explode(' ', $line, 2);
		echo('ID: '.$i.'<br />IP: '.$array[0].'<br />Nazwa: '.$array[1].'<form action="" method="GET"><button name="usun" value="'.$i.'" type="submit">Usuń z listy</button></form>');
		$i++;
	}
	fclose($fh);
	
?>

<br /><br /><b>Dodaj do listy:</b><br />
<form action="" method="GET">
IP:port: <input type="text" name="ip" placeholder="123.123.123.123:20983"/>
Nazwa: <input type="text" name="nazwa" placeholder="Karczma RP"/>
<button name="dodaj" type="submit">Dodaj do listy</button>
</form>


<br /><br /><br /><a href="login.php">Powrót do menu logowania</a><br />
<a href="../index.php">Powrót do strony</a>



<?php else: ?>
<p>Nie masz tu czego szukać przybłędo.</p>
<?php endif; ?>