# trade-dealer-test
После установки переходим в контейнер:
`docker exec -it php81-td bash`
В контейнере:
```
composer instal
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```