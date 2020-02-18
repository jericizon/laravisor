<?php

namespace JericIzon\Laravisor\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use File;

class LaravisorCreate extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'laravisor:create {worker_name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create supervisor configuration';

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
        $laravisorArtisan = config('laravisor.artisan_path');
        $configPath = config('laravisor.conf_path');

        if(!file_exists($laravisorArtisan)){
            return $this->error('Artisan file not found ['.$laravisorArtisan.']. If happened you need to moved artisan file, update "artisan_path" at config/laravisor.php');
        }

        if(!file_exists($configPath)){
            return $this->error('Config path not available ['.$configPath.']. If happened you need to move config path, update "conf_path" at config/laravisor.php');
        }

        $workerName = $this->argument('worker_name');

        if(!$workerName){
            $workerName = $this->ask('Enter unique worker name');
        }

        if(!$workerName) return $this->error('Worker name required.');
        
        $queue = $this->ask('Enter queue');
        
        if(!$queue) return $this->error('Queue required.');

        $queueType = $this->choice('Enter queue type', ['redis', 'sqs', 'database'], 'redis');

        $program = Str::slug($workerName);       

        $workerConf = "{$program}-worker.conf";        

        $optionals = array(
            'sleep' => config('laravisor.sleep'),
            'tries' => config('laravisor.tries'),
            'timeout' => config('laravisor.timeout'),
            'autostart' => config('laravisor.autostart'),
            'autorestart' => config('laravisor.autorestart'),
            'user' => config('laravisor.user'),
            'numprocs' => config('laravisor.numprocs'),
            'redirect_stderr' => config('laravisor.redirect_stderr'),
            'stdout_logfile' => config('laravisor.stdout_logfile') . DIRECTORY_SEPARATOR . "{$program}.log",
        );
        $options = array();    

        if(!$this->confirm('Do you wish to update optional settings?')){
            $this->comment('Using default values...');
            foreach($optionals as $key => $default){
                $options[$key] = $default;
            }
        }
        else{    
            foreach($optionals as $key => $default){
                if(in_array($key, ['autostart', 'autorestart', 'redirect_stderr'])) $default = json_encode($default);
                
                $options[$key] = $this->ask($key . " [$default]");    
                if(!$options[$key]) $options[$key] = $default;
            }    
        }        

        $configFile = fopen($configPath . DIRECTORY_SEPARATOR . $workerConf, 'w');        

        $config = "[program:{$program}-worker]"
                . "\nprocess_name=%(program_name)s_%(process_num)02d"
                . "\ncommand=php {$laravisorArtisan} queue:work {$queueType} --queue=".$queue." --sleep=".$options['sleep']." --tries=".$options['tries']." --timeout=".$options['timeout'].""
                . "\nautostart=".json_encode($options['autostart'])
                . "\nautorestart=".json_encode($options['autorestart'])
                . "\nuser=".$options['user']
                . "\nnumprocs=". $options['numprocs']
                . "\nredirect_stderr=".json_encode($options['redirect_stderr'])
                . "\nstdout_logfile=".$options['stdout_logfile'];

        fwrite($configFile, $config);
        fclose($configFile);

        $this->comment('New supervisor config created: ' . $configPath . DIRECTORY_SEPARATOR . $workerConf);
    }
}
