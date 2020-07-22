<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2a64db26207e5de15518fe9d3092ebf8
{
    public static $files = array (
        '52bad08937d1f3d56ae0465280356dca' => __DIR__ . '/../..' . '/app/Helper/Functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Wang\\' => 5,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Wang\\' => 
        array (
            0 => __DIR__ . '/../..' . '/framework',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2a64db26207e5de15518fe9d3092ebf8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2a64db26207e5de15518fe9d3092ebf8::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}