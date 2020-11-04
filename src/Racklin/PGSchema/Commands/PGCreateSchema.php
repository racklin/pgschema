<?php

namespace Racklin\PGSchema\Commands;

use Illuminate\Console\Command;
use Racklin\PGSchema\PGSchema;

class PGCreateSchema extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'pgschema:create-schema 
                    {schema : The database schema to create}
                    {--database= : The database connection to use}';
    /**
     * @var PGSchema
     */
    private $PGSchema;

    public function __construct(PGSchema $PGSchema)
    {
        $this->PGSchema = $PGSchema;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $database = $this->input->getOption('database');
        $schema = $this->input->getArgument('schema');

        $this->PGSchema->create($schema, $database);

        $this->info(sprintf(
            'Schema "%s" is created',
            $schema
        ));
    }
}
