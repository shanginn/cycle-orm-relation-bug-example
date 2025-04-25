<?php

$autoloadPath = getcwd() . '/vendor/autoload.php';
if (is_file($autoloadPath)) {
    require_once $autoloadPath;
    echo "Autoload file from {$autoloadPath} loaded\n";
} else {
    echo "Autoload file at {$autoloadPath} not found\n";
}