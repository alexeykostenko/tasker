<?php

return [
    'debug' => true,
    'tasks_limit_on_page' => 3,

    //Admin access
    'admin_login' => 'admin',
    'admin_password' => '123',

    //DB
    'host' => 'localhost',
    'database' => 'tasker',
    'username' => 'user',
    'password' => 'password',

    //Image
    'image_folder' => 'storage/images',
    'image_type' => ['JPG', 'JPEG', 'GIF', 'PNG'],
    'image_max_width' => 320,
    'image_max_height' => 240,

    //Twig
    'twig_cache_folder' => 'storage/twig/cache',
    'twig_template_name' => 'layout',
];
