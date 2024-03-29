<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb4e08e94fe2979335bd0a6de6ab73bea
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PhpAmqpLib\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PhpAmqpLib\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-amqplib/php-amqplib/PhpAmqpLib',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb4e08e94fe2979335bd0a6de6ab73bea::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb4e08e94fe2979335bd0a6de6ab73bea::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
