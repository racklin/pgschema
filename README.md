# PostgreSQL Schema manager for Laravel

This is very useful when you are working with multi-tenants
applications with PostgreSQL Schemas.

With facade helper functions, you can create/drop/switch schemas easily.
This package also provide artisan commands for migrations and seeds supports for each schemas.
 
PGSchema will check and only affects when specific connection driver is `pgsql`.
So you can using PGSchema to your projects safely with any database connection drivers without error occurred.

## Laravel version

Current package version works for Laravel `5+` .


## Installation

1. Use composer to add the package into your project
```
composer require racklin/pgschema:dev-master
```

2. Add service provider into your providers array in `config/app.php`
```
Racklin\PGSchema\PGSchemaServiceProvider::class,
```

3. Add alias to aliases array in `config/app.php`
```
'PGSchema' => Racklin\PGSchema\Facades\PGSchema::class,
```

## Artisan Commands
### pgschema:migrate
`pgschema:migrate` add extra `schema` option to Laravel `migrate` command, you can specific the database schema for migrations.
 And it will auto install `migrations` repository table for the schema.
```
Usage:
  pgschema:migrate [options]

Options:
      --database[=DATABASE]  The database connection to use.
      --schema[=SCHEMA]      The database schema to use.
      --force                Force the operation to run when in production.
      --path[=PATH]          The path of migrations files to be executed.
      --pretend              Dump the SQL queries that would be run.
      --seed                 Indicates if the seed task should be re-run.
      --step                 Force the migrations to be run so they can be rolled back individually.
  -h, --help                 Display this help message
  -q, --quiet                Do not output any message
  -V, --version              Display this application version
      --ansi                 Force ANSI output
      --no-ansi              Disable ANSI output
  -n, --no-interaction       Do not ask any interactive question
      --env[=ENV]            The environment the command should run under
  -v|vv|vvv, --verbose       Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
  Run the database migrations
```

### pgschema:rollback
`pgschema:rollback` add extra `schema` option to Laravel `migrate:rollback` command.
```
Usage:
  pgschema:rollback [options]

Options:
      --database[=DATABASE]  The database connection to use.
      --force                Force the operation to run when in production.
      --path[=PATH]          The path of migrations files to be executed.
      --pretend              Dump the SQL queries that would be run.
      --step[=STEP]          The number of migrations to be reverted.
      --schema[=SCHEMA]      The database schema to use
  -h, --help                 Display this help message
  -q, --quiet                Do not output any message
  -V, --version              Display this application version
      --ansi                 Force ANSI output
      --no-ansi              Disable ANSI output
  -n, --no-interaction       Do not ask any interactive question
      --env[=ENV]            The environment the command should run under
  -v|vv|vvv, --verbose       Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
  Rollback the last database migration
```

### pgschema:reset
`pgschema:reset` add extra `schema` option to Laravel `migrate:reset` command.
```
Usage:
  pgschema:reset [options]

Options:
      --database[=DATABASE]  The database connection to use.
      --force                Force the operation to run when in production.
      --path[=PATH]          The path of migrations files to be executed.
      --pretend              Dump the SQL queries that would be run.
      --schema[=SCHEMA]      The database schema to use
  -h, --help                 Display this help message
  -q, --quiet                Do not output any message
  -V, --version              Display this application version
      --ansi                 Force ANSI output
      --no-ansi              Disable ANSI output
  -n, --no-interaction       Do not ask any interactive question
      --env[=ENV]            The environment the command should run under
  -v|vv|vvv, --verbose       Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
  Rollback all database migrations
```

### pgschema:refresh
`pgschema:refresh` add extra `schema` option to Laravel `migrate:refresh` command.
```
Usage:
  pgschema:refresh [options]

Options:
      --database[=DATABASE]  The database connection to use.
      --force                Force the operation to run when in production.
      --path[=PATH]          The path of migrations files to be executed.
      --seed                 Indicates if the seed task should be re-run.
      --seeder[=SEEDER]      The class name of the root seeder.
      --step[=STEP]          The number of migrations to be reverted & re-run.
      --schema[=SCHEMA]      The database schema to use
  -h, --help                 Display this help message
  -q, --quiet                Do not output any message
  -V, --version              Display this application version
      --ansi                 Force ANSI output
      --no-ansi              Disable ANSI output
  -n, --no-interaction       Do not ask any interactive question
      --env[=ENV]            The environment the command should run under
  -v|vv|vvv, --verbose       Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
  Reset and re-run all migrations
```


### pgschema:seed
`pgschema:seed` add extra `schema` option to Laravel `db:seed` command.
```
Usage:
  pgschema:seed [options]

Options:
      --class[=CLASS]        The class name of the root seeder [default: "DatabaseSeeder"]
      --database[=DATABASE]  The database connection to seed
      --schema[=SCHEMA]      The database schema to seed
      --force                Force the operation to run when in production.
  -h, --help                 Display this help message
  -q, --quiet                Do not output any message
  -V, --version              Display this application version
      --ansi                 Force ANSI output
      --no-ansi              Disable ANSI output
  -n, --no-interaction       Do not ask any interactive question
      --env[=ENV]            The environment the command should run under
  -v|vv|vvv, --verbose       Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
  Seed the database with records
```


### pgschema:create-schema
`pgschema:create-schema` create `schema` for `database` .
```
Usage:
  pgschema:create-schema [options] [--] <schema>

Arguments:
  schema                     The database schema to create

Options:
      --database[=DATABASE]  The database connection to use
  -h, --help                 Display this help message
  -q, --quiet                Do not output any message
  -V, --version              Display this application version
      --ansi                 Force ANSI output
      --no-ansi              Disable ANSI output
  -n, --no-interaction       Do not ask any interactive question
      --env[=ENV]            The environment the command should run under
  -v|vv|vvv, --verbose       Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

## Facade Helper Functions
### Create new Schema

`PGSchema::create($schemaName, $databaseName)`

if create is call without $databaseName argument, it using the default connection.


### Drop Schema

`PGSchema::drop($schemaName, $databaseName)`

if drop is call without $databaseName argument, it using the default connection.


### Switch Schema

`PGSchema::schema($schemaName, $databaseName)`

if schema is call without $schemaName argument, it switches to the public
schema (default)
if schema is call without $databaseName argument, it using the default connection.


### Iterating Schemas

`PGSchema::each(Closure $callback, $databaseName)`

if each is call without $databaseName argument, it using the default connection.


## License
MIT: https://racklin.mit-license.org/
