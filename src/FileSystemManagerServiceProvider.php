<?php
namespace MCris112\FileSystemManager;

use Illuminate\Support\ServiceProvider;

class FileSystemManagerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('fileSystemManager', function(){
            return new FileSystemManagerService();
        });
    }

    public function boot(): void
    {
//        $this->app->tag();

        $this->registerMigrations();
    }

    private function registerMigrations(): void
    {
        if(!$this->app->runningInConsole()) return;
        $this->loadMigrationsFrom( dirname(__DIR__) . '/database/migrations' );
    }
}
