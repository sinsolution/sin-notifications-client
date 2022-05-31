<?php 
return [
    'base_url' => env('SIN_NOTIFICATION_URL','http://host.docker.internal:8080'),
    'email'=>env('SIN_NOTIFICATION_API_USER','admin@admin.com'),
    'password'=>env('SIN_NOTIFICATION_API_PASSWORD','password'),
];