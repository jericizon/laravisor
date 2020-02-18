<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laravisor default settings
    |--------------------------------------------------------------------------
    */
    'artisan_path' => base_path('artisan'),
    'conf_path' => config_path('laravisor-conf'),
    'sleep' => 3,
    'tries' => 3,
    'timeout' => 60,
    'autostart' => true,
    'autorestart' => true,
    'user' => 'root',
    'numprocs' => 8,
    'redirect_stderr' => true,
    'stdout_logfile' => storage_path('laravisor-logs')
];
