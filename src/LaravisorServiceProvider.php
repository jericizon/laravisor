<?php

namespace JericIzon\Laravisor;

use Illuminate\Support\ServiceProvider;

class LaravisorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->laravisorPublishable();
            $this->laravisorCommands();
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
    }

    /**
     * Publishable resources.
     */
    private function laravisorPublishable()
    {
        $path = dirname(__DIR__) . '/src/Publishable';

        $this->publishes([
            "{$path}/config/laravisor.php" => config_path('laravisor.php'),
            "{$path}/config/laravisor-conf" => config_path('laravisor-conf'),
        ], 'laravisor-config');

    }

    /**
     * Bugphix Commands
     */

    private function laravisorCommands()
    {
        $this->commands([
            Commands\LaravisorCreate::class,            
            Commands\LaravisorRestart::class,            
        ]);
    }
}
