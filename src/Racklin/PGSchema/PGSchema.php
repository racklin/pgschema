<?php

namespace Racklin\PGSchema;

use Closure;
use DB;

/**
 * Class PGSchema
 *
 * @package Racklin\PGSchema
 */
class PGSchema
{

    /**
     * List all the schemas for a database
     *
     * @param string $databaseName
     *
     * @return mixed
     */
    public function listSchemas($databaseName = null)
    {
        if ($this->getDatabaseDriverName($databaseName) == 'pgsql') {
            $schemas = DB::connection($databaseName)->table('information_schema.schemata')
                ->select('schema_name')
                ->where('schema_name', 'not like', 'pg_%')
                ->where('schema_name', '<>', 'information_schema')
                ->get();

            return $schemas;
        }
        return [];
    }

    /**
     * Check to see if a schema exists
     *
     * @param string $schemaName
     * @param string $databaseName
     *
     * @return bool
     */
    public function schemaExists($schemaName, $databaseName = null)
    {
        if ($this->getDatabaseDriverName($databaseName) == 'pgsql') {
            $schema = DB::connection($databaseName)->table('information_schema.schemata')
                ->select('schema_name')
                ->where('schema_name', '=', $schemaName)
                ->count();

            return ($schema > 0);
        }
        return true;
    }

    /**
     * Check to see if a table exists in schema
     *
     * @param string $tableName
     * @param string $schemaName
     * @param string $databaseName
     *
     * @return bool
     */
    public function tableExists($tableName, $schemaName, $databaseName = null)
    {
        if ($this->getDatabaseDriverName($databaseName) == 'pgsql') {
            $table = DB::connection($databaseName)->table('information_schema.tables')
                ->select('table_schema')
                ->where('table_schema', '=', $schemaName)
                ->where('table_name', '=', $tableName)
                ->count();
            return ($table > 0);
        }
        return true;
    }

    /**
     * Check to see if a column exists in table
     *
     * @param string $columnName
     * @param string $tableName
     * @param string $schemaName
     * @param string $databaseName
     *
     * @return bool
     */
    public function columnExists($columnName, $tableName, $schemaName, $databaseName = null)
    {
        if ($this->getDatabaseDriverName($databaseName) == 'pgsql') {
            $column = DB::connection($databaseName)->table('information_schema.columns')
                ->select('table_schema')
                ->where('table_schema', '=', $schemaName)
                ->where('table_name', '=', $tableName)
                ->where('column_name', '=', $columnName)
                ->count();
            return ($column > 0);
        }
        return true;
    }

    /**
     * Set the search_path to the schema name
     *
     * @param string|array $schemaName
     * @param string $databaseName
     */
    public function schema($schemaName = 'public', $databaseName = null)
    {
        if ($this->getDatabaseDriverName($databaseName) == 'pgsql') {
            if (!is_array($schemaName)) {
                $schemas = [$schemaName];
            }

            // If not connected to database, only setting the schema to database config
            // And laravel PostgresConnection will set search_path after connection created.
            if (!isset(DB::getConnections()[$databaseName])) {
                $this->setDatabaseSchemaConfig($schemaName, $databaseName);
            } else {
                // set connection to schema
                $query = 'SET search_path TO ' . implode(',', $schemas);
                DB::connection($databaseName)->statement($query);
            }
        }
    }

    /**
     * Iterating Schemas
     *
     * Iterating all schemas and swtich back to 'public' schema after iterated.
     *
     * @param Closure $callback
     * @param string $databaseName
     */
    public function each(Closure $callback, $databaseName = null)
    {
        if ($this->getDatabaseDriverName($databaseName) == 'pgsql') {
            $schemas = $this->listSchemas($databaseName);

            $lastSchema = 'public';
            foreach ($schemas as $schema) {
                $this->schema($schema->schema_name, $databaseName);
                $lastSchema = $schema->schema_name;
                $callback();
            }

            // switch back to public schema
            if ($lastSchema != 'public') $this->schema('public', $databaseName);

        } else {
            $callback();
        }
    }

    /**
     * Create a new schema
     *
     * @param string $schemaName
     * @param string $databaseName
     */
    public function create($schemaName, $databaseName = null)
    {
        if ($this->getDatabaseDriverName($databaseName) == 'pgsql') {
            DB::connection($databaseName)->statement('CREATE SCHEMA ' . $schemaName);
        }
    }

    /**
     * Drop an existing schema
     *
     * @param string $schemaName
     * @param string $databaseName
     */
    public function drop($schemaName, $databaseName = null)
    {
        if ($this->getDatabaseDriverName($databaseName) == 'pgsql') {
            DB::connection($databaseName)->statement('DROP SCHEMA ' . $schemaName . ' CASCADE');
        }
    }


    /**
     * Return the current search path
     *
     * @param string $databaseName
     *
     * @return string
     */
    public function getSearchPath($databaseName = null)
    {
        if ($this->getDatabaseDriverName($databaseName) == 'pgsql') {
            $query = DB::connection($databaseName)->select('show search_path');
            $searchPath = array_pop($query)->search_path;
            return $searchPath;
        }
        return '';
    }


    /**
     * Return the database config
     *
     * @param string $databaseName
     *
     * @return string
     */
    protected function getDatabaseConfig($databaseName = null)
    {
        $databaseName = !empty($databaseName) ? $databaseName : app()['config']['database.default'];
        $configKey = 'database.connections.' . $databaseName;
        return  app()['config'][$configKey] ?: [];
    }

    /**
     * Return the database driver name
     *
     * @param string $databaseName
     *
     * @return string
     */
    protected function getDatabaseDriverName($databaseName = null)
    {
        $config = $this->getDatabaseConfig($databaseName);
        return  $config['driver'];
    }

    /**
     * Set the database schema config
     *
     * @param string $schemaName
     * @param string $databaseName
     *
     * @return string
     */
    protected function setDatabaseSchemaConfig($schemaName = 'public', $databaseName = null)
    {
        if (!is_array($schemaName)) {
            $schemas = [$schemaName];
        }

        $databaseName = !empty($databaseName) ? $databaseName : app()['config']['database.default'];
        $configKey = 'database.connections.' . $databaseName;
        $config = app()['config'][$configKey];
        if (!empty($config)) {
            app()['config'][$configKey . '.schema'] = implode(',', $schemas);
        }
    }

}
