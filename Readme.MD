Для генерации сущностей - меняем пространство имен домена, 
для которого нужно создать ORM сущность с репозиторием в файле maker.yaml

root_namespace: '\Domain\Catalog\Doctrine'`

В файле миграций doctrine_migrations.yaml - копируем путь в секции (migrations_paths) и переделываем на свой
И читаем комментарии!!
В файле миграций doctrine.yaml - копируем домен (mappings:) и переделываем в свой 

После чего можем генерировать сущности

Вписываем в services.yaml исключение папки доктрины и добавляем запись репозитория нового домена


bin/console make:entity

php bin/console make:migration - создаёт миграции
php bin/console doctrine:migrations:migrate - выполняет миграции

make:entity ItemViewItemFieldReference - создание сущностей/добавление полей в имеющуюся сущность
make:crud создание контроллера и видов и тестов и типов форм

# API
## JWT
### Генерация ключей для JWT

```
make dev-cli  
php bin/console lexik:jwt:generate-keypair  
```
