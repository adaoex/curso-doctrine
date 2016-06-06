# Doctrine ORM

## Doctrine via console
- criar banco de dados
```
$ cd project/
vendor/doctrine/orm/bin/doctrine orm:schema-tool:create
```

- atualizando banco de dados
```
$ cd project/
vendor/doctrine/orm/bin/doctrine orm:schema-tool:update --force
```

- validação do banco de dados
```
$ cd project/
vendor/doctrine/orm/bin/doctrine orm:validate-schema
```
