<?php

namespace Racklin\PGSchema\Commands;


use Illuminate\Database\Console\Migrations\MigrateCommand;
use DB;

class PGMigrateCommand extends MigrateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pgschema:migrate {--database= : The database connection to use.}
                {--schema= : The database schema to use.}
                {--force : Force the operation to run when in production.}
                {--path= : The path of migrations files to be executed.}
                {--pretend : Dump the SQL queries that would be run.}
                {--seed : Indicates if the seed task should be re-run.}
                {--step : Force the migrations to be run so they can be rolled back individually.}';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        if (! $this->confirmToProceed()) {
            return;
        }

        if (!empty($this->option('schema'))) {
            $this->laravel['pgschema']->schema($this->option('schema'), $this->option('database'));
        }

        // Running Laravel migrate command.
        parent::fire();
    }

}
