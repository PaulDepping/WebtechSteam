# WebtechSteam

University Project

## Linux Setup

```sh
# starting the test server
php -S localhost:8000

# start db
# can also use enable instead of start if needed
systemctl start mariadb.service 

# import db
mariadb -u root -p < sql/db.sql
```

[Link to Register Page](http://localhost:8000/php/register.php)