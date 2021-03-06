<?php namespace Vsch\Generators\Commands;

use Vsch\Generators\Generators\ModelGenerator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Vsch\Generators\GeneratorsServiceProvider;

class ModelGeneratorCommand extends BaseGeneratorCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generate:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new model.';

    /**
     * Create a new command instance.
     *
     * @param ModelGenerator $generator
     */
    public
    function __construct(ModelGenerator $generator)
    {
        parent::__construct();

        $this->generator = $generator;
    }

    /**
     * Get the path to the file that should be generated.
     *
     * @return string
     */
    protected
    function getPath()
    {
        return parent::getSrcPath(self::PATH_MODELS, ucwords($this->argument('name')) . '.php');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected
    function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, 'Name of the model to generate.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected
    function getOptions()
    {
        return $this->mergeOptions(array(
            array('path', null, InputOption::VALUE_OPTIONAL, 'Path to the models directory.', ''),
            array('template', null, InputOption::VALUE_OPTIONAL, 'Path to template.', GeneratorsServiceProvider::getTemplatePath('model.txt'))
        ));
    }
}
