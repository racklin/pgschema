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
        if (DB::connection($databaseName)->getDriverName() == 'pgsql') {
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
        if (DB::connection($databaseName)->getDriverName() == 'pgsql') {
            $schema = DB::connection($databaseName)->table('information_schema.schemata')
                ->select('schema_name')
                ->where('schema_name', '=', $schemaName)
                ->count();

            return ($schema > 0);
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
        if (DB::connection($databaseName)->getDriverName() == 'pgsql') {
            if (!is_array($schemaName)) {
                $schemaName = [$schemaName];
            }

            $query = 'SET search_path TO ' . implode(',', $schemaName);

            DB::statement($query);
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
        if (DB::connection($databaseName)->getDriverName() == 'pgsql') {
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
        if (DB::connection($databaseName)->getDriverName() == 'pgsql') {
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
        if (DB::connection($databaseName)->getDriverName() == 'pgsql') {
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
        if (DB::connection($databaseName)->getDriverName() == 'pgsql') {
            $query = DB::connection($databaseName)->select('show search_path');
            $searchPath = array_pop($query)->search_path;

            return $searchPath;
        }
        return '';
    }
}
