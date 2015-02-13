<?php namespace Vsch\Generators\Commands;

use Vsch\Generators\Generators\ViewGenerator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Vsch\Generators\GeneratorsServiceProvider;

class ViewGeneratorCommand extends BaseGeneratorCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generate:view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new view.';

    /**
     * Model generator instance.
     *
     * @var Vsch\Generators\Generators\ViewGenerator
     */
    protected $generator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ViewGenerator $generator)
    {
        parent::__construct();

        $this->generator = $generator;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('name', InputArgument::REQUIRED, 'Name of the view to generate.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
           array('path', null, InputOption::VALUE_OPTIONAL, 'Path to views directory.', app_path() . '/views'),
           array('template', null, InputOption::VALUE_OPTIONAL, 'Path to template.', GeneratorsServiceProvider::getTemplatePath('view.txt')),
        );
    }

}