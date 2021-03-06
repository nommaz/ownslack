<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit98a410f200e33fcc29819f0e6d550c14
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Component\\EventDispatcher\\' => 34,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Component\\EventDispatcher\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/event-dispatcher',
        ),
    );

    public static $prefixesPsr0 = array (
        'T' => 
        array (
            'Trello\\' => 
            array (
                0 => __DIR__ . '/..' . '/cdaguerre/php-trello-api/lib',
            ),
        ),
        'G' => 
        array (
            'Guzzle\\Tests' => 
            array (
                0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/tests',
                1 => __DIR__ . '/..' . '/guzzle/guzzle/tests',
            ),
            'Guzzle' => 
            array (
                0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/src',
                1 => __DIR__ . '/..' . '/guzzle/guzzle/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit98a410f200e33fcc29819f0e6d550c14::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit98a410f200e33fcc29819f0e6d550c14::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit98a410f200e33fcc29819f0e6d550c14::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
