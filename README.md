# sin-notifications-client

    Api-gateway para entrega de e-mails via api rest

## Instalação

    composer require hillus/sin-notifications-client

## Publicando config 

    php artisan vendor:publish

    escolha a opção Hillus\SinNotifications\SinNotificationMailProvider

## Adicionado configuração de transport

    Caso esteja com uma versão do laravel 7+, é necessário adicionar ao arquivo de configuração mail
    o transport em mailers. 

    ...
    'mailers' => [
        ...
        'sinnotification' => [
            'transport' => 'sinnotification',
        ],
        ...
    ],

## Confirando variaveis de ambiente

    SIN_NOTIFICATION_URL = http://host.docker.internal:8080
    SIN_NOTIFICATION_API_USER=usuario_api
    SIN_NOTIFICATION_API_PASSWORD=password


## Referências

 - https://www.delenamalan.co.za/2020/laravel-custom-mail-driver.html#tests
 - https://alexrusin.com/custom-mail-driver-for-laravel/
