<?php

// get path for project root directory
$rootDir = dirname(__DIR__);

// composer PSR-4 autoloader
require implode(DIRECTORY_SEPARATOR, [$rootDir, 'vendor', 'autoload.php']);

// symfony dotenv loading
$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->load(implode(DIRECTORY_SEPARATOR, [$rootDir, '.env']));

// initialise App Utilities
$utils = new App\Utils($rootDir);

// create and configure Slim app
$app = new App\App([
    'settings' => [
        'addContentLengthHeader' => false,
    ],
], $utils);

// include all route files
foreach ($utils->getRoutesIterator() as $file) {
    include $file->getRealPath();
}

// run app
$app->run();
