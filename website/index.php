<!doctype html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Statystyki serwerów G2O</title>
		<link rel="stylesheet" href="style.css">
		<script src="charts.js?random=<?php echo filemtime('charts.js'); ?>"></script> 
    </head>
    <body style="background-color:rgb(50,50,50);">
		<center>
			<div id="middle">
				<h1>Statystyki serwerów G2O - liczba graczy w ostatnich 30 dniach</h1>
				<h5><script type = "text/javascript">var record = getAllServersRecord(); document.write("Rekord platformy G2O liczony od 18.09.2021: "+record[0]+" graczy, "+record[1]);</script><h5>
				<div id="charts"></div>
				<script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
				<script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
			</div>	
			<h5>Info techniczne: co 30 sekund zapisujemy do bazy danych aktualną listę serwerów oraz liczbę graczy na tychże serwerach. W razie potrzeby dodania nowego serwera na tą stronę proszę o info na Discordzie.</h5>
		</center>
		
		<div id="bottom">
			by <a href="/admin/logowanie.php">Ereno</a><br/>
			Discord:<br />
			Ereno#9339
		</div>
     </body>
</html>                          