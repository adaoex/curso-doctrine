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

Doctrine Query Language:
http://doctrine-orm.readthedocs.io/en/latest/reference/dql-doctrine-query-language.html

The QueryBuilder: 
http://doctrine-orm.readthedocs.io/en/latest/reference/query-builder.html


## DQL Cli

- Testando consultas SQL
```
vendor/doctrine/orm/bin/doctrine dbal:run-sql "SELECT * from cliente"
```

- Testando consultas DQL
```
vendor/doctrine/orm/bin/doctrine orm:run-dql "SELECT c FROM App\Entity\Cliente c order by c.nome desc"
```

