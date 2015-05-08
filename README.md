# QT21-Scorecard

http://www.qt21.eu/scorecard/login

## Installation Instructions

```
git clone https://github.com/multidimensionalquality/qt21-scorecard.git
```

Follow http://symfony.com/doc/current/cookbook/deployment/tools.html

create mysql database, import data/issues.sql

```
cp app/config/parameters.yml.dist app/config/parameters.yml
```
put databse settings in app/config/parameters.yml

```
php app/console doctrine:schema:update -force
```

* Register user via normal scorecard registration form
```
php app/console scorecard:promote-superuser <<username>>
```
to promoter this user to superuser.
