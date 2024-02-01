Projekt: Game Collection

Beschreibung des Projektes:
Dieses Projekt dient dazu Benutzern die Möglichkeit zu geben eine Spielbibliothek zu führen und macht es auch möglich Spiele von allen Benutzern dieses Projektes einzusehen. Benutzer ist es möglich die eigenen Spiele zu verwalten, aber auch von allen anderen Benutzern einzusehen.
 

XAMPP Installieren

1.	Auf https://www.apachefriends.org/de/index.html gehen und die neuste Version von XAMPP installieren.
2.	XAMPP starten und dann um das Projekt nachher zu öffnen muss man das Module Apache und MySQL starten.
 

Projektinhalt runterladen

Das ganze Projekt ist in Github hinterlegt:
https://github.com/Integrilous/ProjektM151
Am besten, wenn man dann auf "<> Code" klickt, lädt man den Inhalt als ZIP runter.

Danach muss man den ZIP Ordner entpacken und den Ordner in den XAMPP Root Ordner legen unter htdocs, die Datenbank sollte dann in den mysql data Ordner reingelegt werden.
Datenbank in der XAMPP Shell erst erstellen(Nachher die _backup Datei restoren und nicht die andere):
Einloggen - mysql -u root
Erstellen - create database 151_data;
Dann die Datenbank importieren in der Shell von XAMPP mit mysql -u root 151_data < "Pfad der gedownloadeten SQL-Datei"


Nachdem Importieren erstellt man ein Benutzerkonto, welches die folgenden Berechtigungen bei dieser Datenbank erhalten muss(NICHT GLOBAL):
SELECT
INSERT
UPDATE
DELETE

Wenn dieser erstellt, muss die dbconnector.php Datei angepasst werden.
Diese Variabeln müssen angepasst werden:
$dbuser (Das erstellte Benutzerkonto eintragen)
$password (Das Passwort des Benutzerkontos eintragen)

Danach kann die Seite durch die URL localhost gestartet werden.
Bei Localhost sieht man alle Verzeichnisse, da einfach ProjektM151 anklicken.
Die Seite lädt dann Problemlos und das Projekt ist dann geladen.

Zuerst muss man ein Benutzerkonto erstellen, auf Registrieren klicken.
Wenn man sich registriert hat, kann man sich mit dem Benutzernamen anmelden.
Nachdem man sich angemeldet hat sieht man sieht die Startseite mit allen Spielen von allen Nutzern.
Bei dem Reiter Meine Spiele kann man dann ein eigenes Spiel hinzufügen, löschen und ändern.
Bei dem Reiter Mein Konto kann man das eigene Passwort ändern.
Der Logout Knopf loggt den momentan eingeloggten User aus.





