<?php namespace Vsch\Generators\Commands;

use Vsch\Generators\Generators\FormDumperGenerator;
use Illuminate\Console\Command;
use Mustache_Engine as Mustache;
use Illuminate\Support\Pluralizer;
use Illuminate\Filesystem\Filesystem as File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FormDumperCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generate:form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dump form HTML for a model';

    /**
     * FormDumper generator instance.
     *
     * @var \Vsch\Generators\Generators\FormDumperGenerator
     */
    protected $generator;

    /**
     * Create a new command instance.
     *
     * @param FormDumperGenerator $generator
     */
    public
    function __construct(FormDumperGenerator $generator)
    {
        parent::__construct();

        $this->generator = $generator;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public 
    function fire() {
        $this->handle();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public 
    function handle()
    {
        if (!class_exists($model = $this->argument('model')))
        {
            throw new \InvalidArgumentException('Model does not exist!');
        }
        $this->generator->setOptions($this->option());

        $this->generator->make(
            $model,
            $this->option('method'),
            $this->option('html')
        );
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
            array('model', InputArgument::REQUIRED, 'Name of the model for the form.'),
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
        return array(
            array('method', null, InputOption::VALUE_OPTIONAL, 'What operation are we doing? [create|edit]', 'create'),
            array('html', null, InputOption::VALUE_OPTIONAL, 'Which HTML element should be used?', 'ul')
        );
    }
}
