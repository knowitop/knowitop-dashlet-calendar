<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit513742419b4f02d959062f66f26cdb82
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit513742419b4f02d959062f66f26cdb82', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit513742419b4f02d959062f66f26cdb82', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        \Composer\Autoload\ComposerStaticInit513742419b4f02d959062f66f26cdb82::getInitializer($loader)();

        $loader->setClassMapAuthoritative(true);
        $loader->register(true);

        return $loader;
    }
}
