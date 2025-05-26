<?php

spl_autoload_register(function ($className) {
    $baseDir = __DIR__ . '/../classes/';
    $className = str_replace('\\', '/', $className);

    $folders = [
        'Models',
        'DAL',
        'Business',
        'Interfaces'
    ];

    foreach ($folders as $folder) {
        $file = $baseDir . $folder . '/' . $className . '.class.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

?>