<?php

spl_autoload_register(function ($class){
    $base_dirs = [
        __DIR__ . '/inc/'
    ];

    $prefix = 'FHF\\OrderGrid\\';

    foreach ($base_dirs as $base_dir){
        $len = strlen($prefix);

        if (strncmp($prefix, $class, $len) !== 0) {
            // If not, move to the next base directory
            continue;
        }

        // Get Relative class
        $relative_class = substr($class, $len);

        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        // If file exists, require it
        if (file_exists($file)){
            require $file;

            return;
        }

    }
});