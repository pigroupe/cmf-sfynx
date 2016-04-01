Doctrine Migration
=====

## Commande

```
php app/console doctrine:migrations
  :diff     Generate a migration by comparing your current database to your mapping information.
  :execute  Execute a single migration version up or down manually.
  :generate Generate a blank migration class.
  :migrate  Execute a migration to a specified version or the latest available version.
  :status   View the status of a set of migrations.
  :version  Manually add and delete migration versions from the version table.
```

## Generate a new migration

```
php app/console doctrine:migrations:generate
```

## To know if a  new migration to execute exist

```
php app/console doctrine:migrations:status --show-versions
```

## To migrate a specified version

```
php app/console doctrine:migrations:migrate YYYYMMDDHHMMSS --no-interaction
```

## To migrate the latest available version

```
php app/console doctrine:migrations:migrate --no-interaction
```