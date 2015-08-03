# QT21-Scorecard

http://www.qt21.eu/scorecard

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

## License

Copyright 2015 Deutsches Forschungszentrum für Künstliche Intelligenz

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

