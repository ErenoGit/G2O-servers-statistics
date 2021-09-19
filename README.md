![bagde](https://img.shields.io/badge/php%20version->=v7.0-green)  ![bagde2](https://img.shields.io/github/languages/top/ErenoGit/G2O-servers-statistics) ![bagde3](https://img.shields.io/github/languages/code-size/ErenoGit/G2O-servers-statistics)

![Screen](https://i.imgur.com/zJPXgEG.jpeg "Screen")

# [PL] Statystyki serwerów G2O
Fanowski, luźny projekt mający na celu ćwiczenie technologii webowych.
Skrypt napisany w PHP pobiera dane dotyczące liczby graczy na serwerach platformy Gothic 2 Online (https://gothic-online.com.pl) z API http://api.gothic-online.com.pl. Następnie dane wpisywane są do bazy danych, do plików SQLite, skąd później dane są przetwarzane na format JSON.
Po wejściu na stronę zaczytywane są wskazane w pliku trackedServers.txt statystyki serwerów (oraz łączna statystyka całej platformy G2O), które następnie są wyświetlane w formie wykresów CanvasJS (https://canvasjs.com).

-----------------------------------------------------------------------------------
# [EN] G2O Servers Statistics
A fan-made, casual project to practice web technologies.
The script written in PHP retrieves data about the number of players on the servers of Gothic 2 Online platform (https://gothic-online.com.pl) from API http://api.gothic-online.com.pl. Then the data is stored in the database, in SQLite files, where later the data is converted into JSON format.
After entering the page, the server statistics specified in the trackedServers.txt file (and total statistics of the whole G2O platform) are loaded, which are then displayed in the form of CanvasJS charts (https://canvasjs.com).

