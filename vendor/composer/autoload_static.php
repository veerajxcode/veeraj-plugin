<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8895fee89517f0c0460f0c972daa0cc7
{
    public static $prefixLengthsPsr4 = array (
        'V' => 
        array (
            'Veeraj\\VeerajPlugin\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Veeraj\\VeerajPlugin\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8895fee89517f0c0460f0c972daa0cc7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8895fee89517f0c0460f0c972daa0cc7::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit8895fee89517f0c0460f0c972daa0cc7::$classMap;

        }, null, ClassLoader::class);
    }
}
