<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Config;

class MailServiceProvider extends ServiceProvider{
    public function register(){
        $config = [
            'driver'     => _settings('MAIL_MAILER'),
            'host'       => _settings('MAIL_HOST'),
            'port'       => _settings('MAIL_PORT'),
            'username'   => _settings('MAIL_USERNAME'),
            'password'   => _settings('MAIL_PASSWORD'),
            'encryption' => _settings('MAIL_ENCRYPTION'),
            'from'       => array('address' => _settings('MAIL_FROM_ADDRESS'), 'name' => _settings('MAIL_FROM_NAME'))
        ];

        Config::set('mail', $config);
    }

    public function boot(){
        
    }
}
