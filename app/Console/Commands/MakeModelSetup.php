<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeModelSetup extends Command
{
    protected $signature = 'make:model:setup {name : The name of the model}';
    protected $description = 'Create a model along with its migration, seeder, observer, and factory';

    public function handle()
    {
        $modelName = $this->argument('name');

        // Generate the model
        $this->call('make:model', [
            'name' => $modelName,
        ]);

        // Generate the migration
        $this->call('make:migration', [
            'name' => 'create_' . strtolower($modelName) . 's_table',
            '--create' => strtolower($modelName) . 's',
        ]);

        // Generate the seeder
        $this->call('make:seeder', [
            'name' => $modelName . 'Seeder',
        ]);

        // Generate the observer
        $this->call('make:observer', [
            'name' => $modelName . 'Observer',
            '--model' => $modelName,
        ]);

        // Generate the factory
        $this->call('make:factory', [
            'name' => $modelName . 'Factory',
            '--model' => $modelName,
        ]);

        $this->info('Model setup completed successfully!');
    }
}
