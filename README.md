# Postgres Schema manager for Laravel

This is very useful when you are working with multi-tenants
applications with Postgresql Schemas.

You can create/drop/switch schemas easily and support migrations.
 

## Installation

1. Use composer to add the package into your project
using
`composer require racklin/pgschema:dev-master`

2. Add 'Racklin\PGSchema\PGSchemaServiceProvider' to your app.php file in the
`services providers` section.
3. Add 'PGSchema' => 'Racklin\PGSchema\Facades\PGSchema' into the `aliases`
section

## Usage

PGSchema will check and only affects when specific connection driver is 'pgsql'.
So you can using PGSchema to your migrations with any database connection drivers without error occurred.

### Migrations Example (every schemas) 
```php
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Racklin\PGSchema\Facades\PGSchema;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        PGSchema::each(function() {

            Schema::create('flights', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('airline');
                $table->timestamps();
            });

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        PGSchema::each(function(){
            Schema::drop('flights');
        });
    }
}
```


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


## Laravel version

Current package version works for Laravel 5.
