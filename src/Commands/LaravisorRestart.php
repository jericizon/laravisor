<?php

namespace JericIzon\Laravisor\Commands;

use Illuminate\Console\Command;

class LaravisorRestart extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'laravisor:restart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restart supervisor configuration';

    /**
     * Execute the console command.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     *
     * @return void
     */
    
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {        

        $timeStarted = microtime(true);      
        
        $command = array(
            'Stopping supervisor' => 'stop all',
            'Reading config' => 'reread',
            'Updating config' => 'update',
            'Starting supervisor' => 'start all',
        );
        
        if ( count( $command ) ) {
            foreach ($command as $key => $cmd) {
                $this->line($key);
                shell_exec("sudo supervisorctl {$cmd}");
            }
        }
        
        $executionTime = number_format( microtime(true) - $timeStarted, 2);
        $this->comment("Supervisor restarted! ({$executionTime} seconds)");
    }
}
