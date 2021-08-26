<?php

return [
    'aliases' => [
        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
//        'Fractal' => \Spatie\Fractal\Facades\Fractal::class,
    ],
    'locale' => [
        'es'
    ],
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
];
