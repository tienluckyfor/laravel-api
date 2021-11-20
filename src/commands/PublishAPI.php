<?php

namespace Codeby\LaravelApi\commands;

use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;

class PublishAPI extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laravel-api:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Service Worker|Offline HTMl|manifest file for API application.';

    public $composer;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->composer = app()['composer'];
    }



    public function copyFile($file, $pathTo, $fileTo)
    {
        $pathFrom = __DIR__.'/../stubs/'.$file;
        $fileData = file_get_contents($pathFrom);
        $this->createFile($pathTo.DIRECTORY_SEPARATOR, $fileTo, $fileData);
        $this->info('Copied File ['.$pathFrom.'] To ['.$pathTo.DIRECTORY_SEPARATOR.$fileTo.']');
    }

    public function handle()
    {
        $this->copyFile('codeby.php', config_path(), 'codeby.php');
        $this->copyFile('env.stub', resource_path('views/sites/demo-site'), '.env');
        $this->copyFile('index.blade.php', resource_path('views/sites/demo-site'), 'index.blade.php');
        $this->copyFile('user-page.blade.php', resource_path('views/sites/demo-site'), 'user-page.blade.php');

        $this->info('Generating autoload files');
        $this->composer->dumpOptimized();
        $this->info('Enjoy at: '.url('/sites/demo-site'));
    }

    public static function createFile($path, $fileName, $contents)
    {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $path = $path.$fileName;

        file_put_contents($path, $contents);
    }
}
