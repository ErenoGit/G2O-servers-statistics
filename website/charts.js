function loadFile(filePath) {
  var result = null;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.open("GET", filePath, false);
  xmlhttp.send();
  if (xmlhttp.status==200) {
	result = xmlhttp.responseText;
  }
  return result;
}
	
const splitLines = str => str.split(/\r?\n/);
	
function getAllServersRecord()
{
	var allServersRecord = loadFile("database/allServersRecord.txt?random="+Math.floor(Math.random() * 100));
	var splittedallServersRecord = splitLines(allServersRecord);
	
	return splittedallServersRecord;
}

window.onload = function() {
	
	String.prototype.replaceAt = function(index, replacement) {
		return this.substr(0, index) + replacement + this.substr(index + replacement.length);
	}

	///////////////////////////  SPECIAL static allServers chart
	var dataPoints0 = [];
	
	var div = document.createElement("div");
	div.setAttribute("id", "0");
	div.style.width = "90%";
	div.style.height = "0px";
	document.getElementById("charts").appendChild(div);
	
	var br = document.createElement("br");
	div.parentNode.insertBefore(br, div.nextSibling);

	
	var chart0 = new CanvasJS.Chart("0", {
		animationEnabled: true,
		theme: "dark2",
		zoomEnabled: true,
		title: {
			text: "Łącznie na platformie G2O"
		},
		axisY: {
			title: "Liczba graczy",
			titleFontSize: 24
		},
		axisX:{
		   valueFormatString:"DD-MM-YYYY"
		},
		data: [{
		type: "line",
		xValueFormatString: "DD-MM-YYYY HH:mm",
		dataPoints: dataPoints0
		}]
	});
				
	function addData0(data) {
		var dps = data.players;
					
		for (var i = 0; i < dps.length; i++) {
			var dateFromDatabase = dps[i][0];
						
			var year = dateFromDatabase.substr(0, 4);
			var month = dateFromDatabase.substr(5, 2);
			var day = dateFromDatabase.substr(8, 2);
			var hour = dateFromDatabase.substr(11, 2);
			var minute = dateFromDatabase.substr(14, 2);
			var second = dateFromDatabase.substr(17, 2);
						
			var formatedTime = Date.parse(year+"-"+month+"-"+day+"T"+hour+":"+minute+":"+second);
						
			dataPoints0.push({
				x: new Date(formatedTime),
				y: dps[i][1]
			});
		}	
		chart0.render();
	}
				
				
	$.getJSON("database/json/allServers.json", addData0);
	
	document.getElementById(0).style.height = "340px";
	///////////////////////////
	
	///////////////////////////  SPECIAL static Axyl chart
	var dataPoints1 = [];
	
	var div = document.createElement("div");
	div.setAttribute("id", "1");
	div.style.width = "90%";
	div.style.height = "0px";
	document.getElementById("charts").appendChild(div);
	
	var br = document.createElement("br");
	div.parentNode.insertBefore(br, div.nextSibling);
	
	var chart1 = new CanvasJS.Chart("1", {
		animationEnabled: true,
		theme: "dark2",
		zoomEnabled: true,
		title: {
			text: "Axyl MMORPG"
		},
		axisY: {
			title: "Liczba graczy",
			titleFontSize: 24
		},
		axisX:{
		   valueFormatString:"DD-MM-YYYY"
		},
		data: [{
		type: "line",
		xValueFormatString: "DD-MM-YYYY HH:mm",
		dataPoints: dataPoints1
		}]
	});
				
	function addData1(data) {
		var dps = data.players;
					
		for (var i = 0; i < dps.length; i++) {
			var dateFromDatabase = dps[i][0];
						
			var year = dateFromDatabase.substr(0, 4);
			var month = dateFromDatabase.substr(5, 2);
			var day = dateFromDatabase.substr(8, 2);
			var hour = dateFromDatabase.substr(11, 2);
			var minute = dateFromDatabase.substr(14, 2);
			var second = dateFromDatabase.substr(17, 2);
						
			var formatedTime = Date.parse(year+"-"+month+"-"+day+"T"+hour+":"+minute+":"+second);
						
			dataPoints1.push({
				x: new Date(formatedTime),
				y: dps[i][1]
			});
		}	
		chart1.render();
	}
				
				
	$.getJSON("database/json/axyl.json", addData1);
	
	document.getElementById(1).style.height = "340px";
	///////////////////////////
	
	
	
	var trackedServers = loadFile("trackedServers.txt?random="+Math.floor(Math.random() * 100));
	var splittedTrackedServers = splitLines(trackedServers);
	
	//we start from 2, becouse 0 is special allServers, 1 is special Axyl
	var servicedServersCount = 2;
	
	for (let line of splittedTrackedServers) {
		
		eval('var dataPoints'+servicedServersCount+' = [];');
		
		var div = document.createElement("div");
		div.setAttribute("id", servicedServersCount);
		div.style.width = "90%";
		div.style.height = "0px";
		document.getElementById("charts").appendChild(div);
		
		var br = document.createElement("br");
		div.parentNode.insertBefore(br, div.nextSibling);
		
		var ipAndPort = line.substr(0,line.indexOf(' ')); // ip and port
		var serverName = line.substr(line.indexOf(' ')+1); // server name
		var ipAndPortOnlyDots = ipAndPort.replaceAt(ipAndPort.lastIndexOf(":"), ".");
		
		eval('var chart' + servicedServersCount + ' = new CanvasJS.Chart("'+servicedServersCount+'", { animationEnabled: true, theme: "dark2", zoomEnabled: true, title: { text: serverName+" ("+ipAndPort+")" }, axisY: { title: "Liczba graczy", titleFontSize: 24 }, axisX:{ valueFormatString:"DD-MM-YYYY" }, data: [{ type: "line", xValueFormatString: "DD-MM-YYYY HH:mm", dataPoints: dataPoints'+servicedServersCount+' }] });');
		
		eval('function addData'+servicedServersCount+'(data) {var dps = data.players; for (var i = 0; i < dps.length; i++) {var dateFromDatabase = dps[i][0]; var year = dateFromDatabase.substr(0, 4); var month = dateFromDatabase.substr(5, 2); var day = dateFromDatabase.substr(8, 2); var hour = dateFromDatabase.substr(11, 2); var minute = dateFromDatabase.substr(14, 2); var second = dateFromDatabase.substr(17, 2); if(second === ""){ second = "00";} var formatedTime = Date.parse(year+"-"+month+"-"+day+"T"+hour+":"+minute+":"+second); dataPoints'+servicedServersCount+'.push({x: new Date(formatedTime), y: dps[i][1] }); } chart'+servicedServersCount+'.render();}');

		eval('$.getJSON("database/json/'+ipAndPortOnlyDots+'.json", addData'+servicedServersCount+');');

        document.getElementById(servicedServersCount).style.height = "340px";
		
		servicedServersCount++;
	}
	
	
}