<?php

declare(strict_types=1);

use App\Application\Kernel;

// If you forgot to configure some of this in your php.ini file,
// then don't worry, we will set the standard environment
// settings for you.

\mb_internal_encoding('UTF-8');
\error_reporting(E_ALL & ~(E_USER_DEPRECATED | E_DEPRECATED));
\ini_set('display_errors', 'stderr');

// Application helper functions. Must be included before the composer autoloader.
require __DIR__ . '/functions.php';

// Register Composer's auto loader.
require __DIR__ . '/vendor/autoload.php';


// Initialize shared container, bindings, directories and etc.
$app = Kernel::create(
    directories: ['root' => __DIR__],
)->run();

if ($app === null) {
    exit(255);
}

$code = (int)$app->serve();
exit($code);
