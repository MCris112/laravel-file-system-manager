<?php
namespace MCris112\FileSystemManager;

use Illuminate\Support\ServiceProvider;

class FileSystemManagerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('fileSystemManager', function(){
            return new FileSystemManagerService(config('filesystems.default'));
        });

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/config/filesystemmanager.php', 'filesystemmanager'
        );
    }

    public function boot(): void
    {
//        $this->app->tag();

        $this->loadRoutesFrom(dirname(__DIR__).'/routes/web.php');
        $this->registerMigrations();

        $this->registerPublishing();
    }

    private function registerMigrations(): void
    {
        if(!$this->app->runningInConsole()) return;
        $this->loadMigrationsFrom( dirname(__DIR__) . '/database/migrations' );
    }

    protected function registerPublishing()
    {
        if(!$this->app->runningInConsole()) return;

        $this->publishes([
            dirname(__DIR__) . '/config/filesystemmanager.php' => $this->app->configPath('filesystemmanager.php'),
        ], 'filesystemmanager.config');

        $this->publishes([
            dirname(__DIR__) . '/database/migrations' => $this->app->databasePath('migrations')
        ], 'filesystemmanager.migrations');
    }
}
