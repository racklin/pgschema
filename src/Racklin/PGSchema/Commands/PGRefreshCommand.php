<?php

namespace Racklin\PGSchema\Commands;

use Symfony\Component\Console\Input\InputOption;
use Illuminate\Database\Console\Migrations\RefreshCommand;

class PGRefreshCommand extends RefreshCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'pgschema:refresh';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (! $this->confirmToProceed()) {
            return;
        }

        if (!empty($this->option('schema'))) {
            $this->laravel['pgschema']->schema($this->option('schema'));
        }

        // Running Laravel refresh command.
        parent::handle();

    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge(parent::getOptions(), [
            ['schema', null, InputOption::VALUE_OPTIONAL, 'The database schema to use'],
        ]);
    }

}
