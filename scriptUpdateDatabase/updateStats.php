<?php

	//ścieżka do folderu, w którym znajdują się pliki strony G2O serwer staty
	$GLOBALS['pathToWebsiteDirectory'] = '/home/ubuntu/webpage';
	
	//liczba dni z których zbierane są logi dot. liczby graczy na serwerach (oraz łącznie na platformie G2O)
	$GLOBALS['numberOfDays'] = 30;
	
	//liczba sekund co jaki zapisywane są liczby graczy w bazie danych, to raczej na sztywno i tego nie ruszać!
	$GLOBALS['secondsEveryLogsAreSaving'] = 30;
	
	
	

	// Method: POST, PUT, GET etc
	// Data: array("param" => "value") ==> index.php?param=value
	function CallAPI($method, $url, $data = false)
	{
		$curl = curl_init();
		switch ($method)
		{
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);

				if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_PUT, 1);
				break;
			default:
				if ($data)
					$url = sprintf("%s?%s", $url, http_build_query($data));
		}

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);

		curl_close($curl);

		return $result;
	}
	
	function CreateOrOpenDB($address)
	{
		if (!file_exists($GLOBALS['pathToWebsiteDirectory'].'/database/'.$address.'.db')) {
			$createOrOpenDB = new SQLite3($GLOBALS['pathToWebsiteDirectory'].'/database/'.$address.'.db');
			$createOrOpenDB->exec("CREATE TABLE numberOfPlayers(date TEXT, number INT)");
			if($address == 'allServers')
			{
				$createOrOpenDB->exec("CREATE TABLE record(date TEXT, number INT)");
				$createOrOpenDB->exec("INSERT INTO record(date, number) VALUES('".date('Y-m-d H:i:s')."', 0)");
			}
			return $createOrOpenDB;
		}
		else
		{
			$createOrOpenDB = new SQLite3($GLOBALS['pathToWebsiteDirectory'].'/database/'.$address.'.db');
			return $createOrOpenDB;
		}
	}
	
	function InsertNumberOfPlayersToDatabase($db, $number)
	{
		$db->exec("INSERT INTO numberOfPlayers(date, number) VALUES('".date('Y-m-d H:i:s')."', ".$number.")");
	}
	
	function UpdateAllServersRecord($db, $number)
	{
		$db->exec("UPDATE record SET date = '".date('Y-m-d H:i:s')."', number = ".$number." WHERE number < ".$number);
	}
	
	function GetAllServersRecord($db)
	{
		$result = $db->query('SELECT number FROM record');
		$record = 0;
		
		while($res = $result->fetchArray(SQLITE3_ASSOC)){
             if(!isset($res['number'])) continue;
              $record = $res['number'];
          }
		
		return $record;
	}
	
	function ClearDatabaseOldLogs($db)
	{
		$secondsOnNumberOfDays = $GLOBALS['numberOfDays'] * 24 * 60 * 60;   //7 dni to 604800 sekund, 14 dni to 1209600 sekund       30 dni to 2592000 sekund
		$limit = $secondsOnNumberOfDays / $GLOBALS['secondsEveryLogsAreSaving'];	
		
		$db->exec("DELETE FROM numberOfPlayers WHERE rowid NOT IN (SELECT rowid FROM numberOfPlayers ORDER BY date DESC limit ".$limit.")");
	}
	
	function GetNumberOfPlayersFromDatabase($db)
	{
		$result = $db->query('SELECT * FROM numberOfPlayers');
		
        $array = array();

        $i = 0;

         while($res = $result->fetchArray(SQLITE3_ASSOC)){
             if(!isset($res['date'])) continue;

              $array[$i]['date'] = $res['date'];
              $array[$i]['number'] = $res['number'];

              $i++;
          }
		
		return $array;
	}
	
	
	function mainFunction()
	{
		$listOfServersJSON = CallAPI("GET", "http://api.gothic-online.com.pl/master/public_servers/");
		$listOfServers = json_decode($listOfServersJSON, true);
		$allPlayers = 0;
		$allAxylPlayers = 0;

		foreach ($listOfServers as &$serverAddressInfo) 
		{
			$address = $serverAddressInfo["ip"].":".$serverAddressInfo["port"];
			
			$serverInfoJSON = CallAPI("GET", "http://api.gothic-online.com.pl/master/server/".$address."/");
			$serverInfo = json_decode($serverInfoJSON, true);
			
			$serverInfo = $serverInfo["info"];
			
			$db = CreateOrOpenDB($address);
			InsertNumberOfPlayersToDatabase($db, $serverInfo["players"]);
			$allPlayers = $allPlayers + (int)$serverInfo["players"];
			
			if($serverAddressInfo["ip"] == "146.59.23.8")
			{
				$allAxylPlayers = $allAxylPlayers + (int)$serverInfo["players"];
			}
			
			ClearDatabaseOldLogs($db);
			
			$serverData = GetNumberOfPlayersFromDatabase($db);
			
			$jsonData = json_encode($serverData);
			
			$jsonData = str_replace('{', '[', $jsonData);
			$jsonData = str_replace('}', ']', $jsonData);
			
			$jsonData = str_replace('"number":', '', $jsonData);
			$jsonData = str_replace('"date":', '', $jsonData);
				
			$jsonData = substr_replace($jsonData, '{"players":[', 0, 1);
			$jsonData = substr_replace($jsonData, ']}', -1);
			
			$formatedAddress = str_replace(':', '.', $address);
			file_put_contents($GLOBALS['pathToWebsiteDirectory'].'/database/json/'.$formatedAddress.'.json', $jsonData);
		}
		
		/////////////////////////// special allServers
		$dbAll = CreateOrOpenDB('allServers');
		InsertNumberOfPlayersToDatabase($dbAll, $allPlayers);
		ClearDatabaseOldLogs($dbAll);
		$serverData = GetNumberOfPlayersFromDatabase($dbAll);
			
		$jsonData = json_encode($serverData);
			
		$jsonData = str_replace('{', '[', $jsonData);
		$jsonData = str_replace('}', ']', $jsonData);
			
		$jsonData = str_replace('"number":', '', $jsonData);
		$jsonData = str_replace('"date":', '', $jsonData);
				
		$jsonData = substr_replace($jsonData, '{"players":[', 0, 1);
		$jsonData = substr_replace($jsonData, ']}', -1);
		
		file_put_contents($GLOBALS['pathToWebsiteDirectory'].'/database/json/allServers.json', $jsonData);
		
		$allServersRecord = GetAllServersRecord($dbAll);
		UpdateAllServersRecord($dbAll, $allPlayers);
		if((int)$allServersRecord < $allPlayers)
		{
			file_put_contents($GLOBALS['pathToWebsiteDirectory'].'/database/allServersRecord.txt', $allPlayers.PHP_EOL.date('Y-m-d H:i:s'));
		}
		///////////////////////////
		
		
		
		/////////////////////////// special Axyl
		$dbAxyl = CreateOrOpenDB('axyl');
		InsertNumberOfPlayersToDatabase($dbAxyl, $allAxylPlayers);
		ClearDatabaseOldLogs($dbAxyl);
		$serverData = GetNumberOfPlayersFromDatabase($dbAxyl);
			
		$jsonData = json_encode($serverData);
			
		$jsonData = str_replace('{', '[', $jsonData);
		$jsonData = str_replace('}', ']', $jsonData);
			
		$jsonData = str_replace('"number":', '', $jsonData);
		$jsonData = str_replace('"date":', '', $jsonData);
				
		$jsonData = substr_replace($jsonData, '{"players":[', 0, 1);
		$jsonData = substr_replace($jsonData, ']}', -1);
		
		file_put_contents($GLOBALS['pathToWebsiteDirectory'].'/database/json/axyl.json', $jsonData);
		///////////////////////////
		
		
		
	}
	
	//run that script every 30 seconds (crontab is set to every minute)
	mainFunction();
	sleep(30);
	mainFunction();
	
	
?>