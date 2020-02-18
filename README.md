# Laravisor
Generate laravel supervisor configuration in easiest way.

Before proceeding make sure you already have supervisor installed into your server. 
https://laravel.com/docs/master/queues#supervisor-configuration

## Configure supervisor

Update supervisor config, edit the file or equivalent.
/etc/supervisor/supervisord.conf

Update line below:
```
[include]
files = {your_project_root_path}/config/laravisor-conf/*.conf
```

## Installation
    $ composer require jericizon/laravisor

### Publish config files
    $ php artisan vendor:publish --tag=laravisor-config


### Create supervisor config file
    $ php artisan laravisor:create {worker_name}

### Restart supervisor
    $ php artisan laravisor:restart
    
## License

MIT

Copyright (c) 2020, Jeric
