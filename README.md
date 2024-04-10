# TaskMaster

- [TaskMaster](#taskmaster)
  - [Installation](#installation)
  - [Usage](#usage)
  - [Database setup](#database-setup)

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

Now take the entire content of the `src` directory and move it to the root of the `htdocs` directory.

The directory structure should look like this:

```plaintext
htdocs
│__ index.php
|__ dashboard.php
|__ create_project.php
|__ edit_project.php
|__ account
|__|__ login.php
|__|__ register.php
|__|__ logout.php
|__|__ login_register.php
```

## Database setup

In the XAMPP control panel, start the MySQL module via the shell.

Login with the `root` user

```bash
mysql -u root
```

Create a new database

```sql
create database taskmaster;

use taskmaster;
```

Create the tables

```sql
CREATE TABLE `project` (
  `project_id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `project_lead` varchar(100) NOT NULL,
  PRIMARY KEY (`project_id`)
);

CREATE TABLE `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `password` varchar(1000) NOT NULL,
  PRIMARY KEY (`user_id`)
);
```
