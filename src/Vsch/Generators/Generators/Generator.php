<?php

namespace Vsch\Generators\Generators;

use Vsch\Generators\Cache;
use Illuminate\Filesystem\Filesystem as File;

class RequestedCacheNotFound extends \Exception {}

abstract class Generator {

    /**
     * File path to generate
     *
     * @var string
     */
    public $path;

    /**
     * File system instance
     * @var File
     */
    protected $file;

    /**
     * Cache
     * @var Cache
     */
    protected $cache;

    /**
     * Constructor
     *
     * @param $file
     */
    public function __construct(File $file, Cache $cache)
    {
        $this->file = $file;
        $this->cache = $cache;
    }

    /**
     * Compile template and generate
     *
     * @param  string $path
     * @param  string $template Path to template
     * @return boolean
     */
    public function make($path, $template)
    {
        $this->name = basename($path, '.php');
        $this->path = $this->getPath($path);
        $template = $this->getTemplate($template, $this->name);

        // check for migration by same name but new date, I'm tired of deleting these files
        $filename = basename($this->path, '');
        $name = strtolower($this->name) . '.php';
        $fileExists = false;

        if ($filename !== $name && strpos($filename, $name) !== false)
        {
            // must have a date prefix, we should check if a file by the same name exists and append .new to this one
            if ($handle = opendir(dirname($path)))
            {
                while (false !== ($entry = readdir($handle)))
                {
                    if (fnmatch('*_'.$name, $entry, FNM_PERIOD))
                    {
                        $fileExists = true;
                        break;
                    }
                }
                closedir($handle);
            }
        }

        if (!$fileExists && !$this->file->exists($this->path))
        {
            return $this->file->put($this->path, $template) !== false;
        }
        else
        {
            // put it as .new, and delete previous .new
            if ($this->file->exists($this->path . ".new")) unlink($this->path . ".new");
            if (!$this->file->exists($this->path . ".new")) $this->file->put($this->path . ".new", $template) !== false;
            return false; // we didn't really create it
        }

        return false;
    }

    /**
     * Get the path to the file
     * that should be generated
     *
     * @param  string $path
     * @return string
     */
    protected function getPath($path)
    {
        // By default, we won't do anything, but
        // it can be overridden from a child class
        return $path;
    }

    /**
     * Determines whether the specified template
     * points to the scaffolds directory
     *
     * @param  string $template
     * @return boolean
     */
    protected function needsScaffolding($template)
    {
        return str_contains($template, 'scaffold');
    }

    /**
     * Get compiled template
     *
     * @param  string $template
     * @param  $name Name of file
     * @return string
     */
    abstract protected function getTemplate($template, $name);
}
