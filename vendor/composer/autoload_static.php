<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8a90303bc740f12492b9143dd1c9b1d5
{
    public static $prefixLengthsPsr4 = array (
        'r' => 
        array (
            'redis\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'redis\\' => 
        array (
            0 => __DIR__ . '/../..' . '/redis',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8a90303bc740f12492b9143dd1c9b1d5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8a90303bc740f12492b9143dd1c9b1d5::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}