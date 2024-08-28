#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../init_autoloader.php';
// Fix PATH problems
chdir(dirname(__DIR__));
$console = new \Symfony\Component\Console\Application();
$conf    = require __DIR__ . '/../config/application.config.php';
$app     = Laminas\Mvc\Application::init($conf);


$commands = [];
// On parse automatiquement le dossier des commandes
$modules = $conf['modules'];


function scanCommandFiles($modules)
{
    $path = __DIR__ . '/../module';
    global $app;

    $result = [];
    foreach ($modules as $module) {
        $dir = $modulePath = __DIR__ . '/../module/' . $module . '/src/'.$module.'/Command';
        if (is_dir($dir)) {
            global $app;
            $output = [];
            $re     = '/.*Command\.php/m';
            $scan   = scandir($dir);
            foreach ($scan as $key => $value) {
                if (!in_array($value, ['.', '..'])) {
                    if (preg_match($re, $value, $matches)) {
                        $class     = substr($value, 0, strlen($value) - 4);
                        $reflector = new ReflectionClass($module . '\Command\\' . $class);
                        if ($reflector->isSubclassOf(\Symfony\Component\Console\Command\Command::class)) {
                            $result[] = $reflector->newInstanceArgs([$app->getServiceManager()]);
                        }
                    }
                }
            }
        }
    }
    return $result;
}


$commands = scanCommandFiles($modules);
$console->addCommands($commands);

$console->run();
