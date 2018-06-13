<?php
return [
    'supported_locales' => ['en'],
    'supported_formats' => ['png', 'jpg', 'JPG', 'gif'],
    'default_separator' => '/',
    'alternate_separator' => '_',
    'namespace' => [
        'controllers' => 'Totti619\\Gallery\\Libraries\\Controllers',
    ],
    'route' => [
        'prefix' => 'gallery',
    ],
    'views' => [
        'index' => [
            'extends' => [
                'gallery::main'
            ],
            'section' => [
                'content',
                'scripts'
            ],

        ]
    ],
//    'gallery_path' => __DIR__ . '/../assets/img/gallery',
    'meta' => [
        'path' => public_path() . '/vendor/totti619/gallery/meta',
        'json_filename' => 'meta.json',
        'space_separator' => '-',
        'data_attribute_prefix' => 'data-',
        'generated_meta_prefix' => 'file_'
    ],
    'gallery_path' => public_path() . '/vendor/totti619/gallery/img',
    'gallery_url' => url('/vendor/totti619/gallery/img') . '/',
];