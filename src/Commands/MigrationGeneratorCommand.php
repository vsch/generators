<?php namespace Vsch\Generators\Commands;

use Vsch\Generators\Generators\MigrationGenerator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Vsch\Generators\GeneratorsServiceProvider;

class MigrationGeneratorCommand extends BaseGeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generate:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new migration.';

    /**
     * Model generator instance.
     *
     * @var \Vsch\Generators\Generators\MigrationGenerator
     */
    protected $generator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MigrationGenerator $generator)
    {
        parent::__construct();

        $this->generator = $generator;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $name = $this->argument('name');
        $path = $this->getPath();
        $this->generator->setOptions($this->option());

        // common error for field types
        $fields = $this->option('fields');
        $fields = GeneratorsServiceProvider::mapFieldTypes($fields);

        $this->fields = $fields;

        $created = $this->generator
                        ->parse($name, $fields)
                        ->make($path, null);

        //$this->call('dump-autoload');

        $this->printResult($created, $path);
    }

    /**
     * Get the path to the file that should be generated.
     *
     * @return string
     */
    protected function getPath()
    {
        return parent::getSrcPath('/database/migrations', '/' . ucwords($this->argument('name')) . '.php', '/migrations');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, 'Name of the migration to generate.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return $this->mergeOptions(array(
            array('path', null, InputOption::VALUE_OPTIONAL, 'The path to the migrations folder', ''),
            array('fields', null, InputOption::VALUE_OPTIONAL, 'Table fields', null)
        ));
    }

}