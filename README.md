# TaskMaster

- [TaskMaster](#taskmaster)
  - [Installation](#installation)
  - [Usage](#usage)

## Installation

Download the latest version of XAMPP from [Apache](https://www.apachefriends.org/de/index.html) and install it.

Start XAMPP with its Apache and MySQL modules.

## Usage

Clone the repository to the local `htdocs` directory in the XAMPP directory at `C:\xampp`.

cd into `xampp/htdocs`

```bash
cd C:\xampp\htdocs
```

Clone the repository

```bash
git clone https://github.com/leonden/M-306
```

The database should then be placed in the mysql data folder.

Die Datenbank sollte dann in den mysql data Ordner reingelegt werden.
Datenbank in der XAMPP Shell erst erstellen(Nachher die \_backup Datei restoren und nicht die andere):
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

`$dbuser` (Das erstellte Benutzerkonto eintragen)

`$password` (Das Passwort des Benutzerkontos eintragen)

Danach kann die Seite durch die URL localhost gestartet werden.
Bei Localhost sieht man alle Verzeichnisse, da einfach ProjektM151 anklicken.
Die Seite lädt dann Problemlos und das Projekt ist dann geladen.

Zuerst muss man ein Benutzerkonto erstellen, auf Registrieren klicken.
Wenn man sich registriert hat, kann man sich mit dem Benutzernamen anmelden.
Nachdem man sich angemeldet hat sieht man sieht die Startseite mit allen Spielen von allen Nutzern.
Bei dem Reiter Meine Spiele kann man dann ein eigenes Spiel hinzufügen, löschen und ändern.
Bei dem Reiter Mein Konto kann man das eigene Passwort ändern.
Der Logout Knopf loggt den momentan eingeloggten User aus.
