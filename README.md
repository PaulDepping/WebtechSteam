# WebtechSteam

## Rollenverteilung
* Paul Simon Depping, 1715018
  * PHP-Code
  * SQL-Code
  * README
* Jannis Leonard Goltermann, 1714572
  * Erster Prototyp HTML&PHP-Code Main Page
  * Suchfunktion SQL-Code
  * Wireframe-Skizzen
  * Webdesign Login & Register
* Aslan Khajik, 1715144
  * Wireframe-Skizzen
  * Webdesign Main Page
* Maxim Malkov, 1714776
  * Webdesign Main Page

## Linux Example Setup

In `www/config.php` kann der Login für die Datenbank angepasst werden. (Nutzername, Passwort, URL etc. pp.)

```sh
# start db
# can also use enable instead of start if needed
systemctl start mariadb.service

# import db
mariadb -u root -p < db/db.sql

# start python REST-API (in separate terminal)
python www/rest.py

# starting the test server (in separate terminal)
php -S localhost:8000
```

## Login

Nutzer können auf der Register Page selbst eingerichtet werden. Es gibt aber bereits einen User:

* Username: demo
* Passwort: demo

[Link to Register Page](http://localhost:8000/www/register.php)
