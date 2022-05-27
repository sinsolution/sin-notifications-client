<?php 
return [
    'base_url' => env('CUSTOM_MAIL_URL','http://host.docker.internal:8080'),
    // 'key' => env('CUSTOM_MAIL_API_KEY','123456'),
    'email'=>env('CUSTOM_MAIL_API_USER','admin@admin.com'),
    'password'=>env('CUSTOM_MAIL_API_PASSWORD','password'),
];