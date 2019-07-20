<?php

namespace App;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Application utility class
 * An instance of this Class is stored in the App instance for use by other components
 *
 * @package App
 */
class Utils
{
    /**
     * @var string Project root directory (defined in public/index.php)
     */
    private $rootDir;

    /**
     * @var string Application source directory
     */
    private $appRoot = 'src';

    /**
     * @var string Views directory
     */
    private $viewsDir = 'views';

    /**
     * Utils constructor.
     * @param string $directory
     */
    public function __construct(string $directory)
    {
        $this->rootDir = $directory;
    }

    /**
     * Returns full Project root path
     * @return string|string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * Returns full Application root path
     * @return string
     */
    public function getAppRoot()
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getRootDir(), $this->appRoot]);
    }

    /**
     * Returns an object that iterates over all Project route files
     * @return RegexIterator
     */
    public function getRoutesIterator()
    {
        // find all PHP files inside the src/routes directory
        $path = implode(DIRECTORY_SEPARATOR, [$this->getAppRoot(), 'routes']);
        $directoryIterator = new RecursiveDirectoryIterator($path);
        $iteratorIterator = new RecursiveIteratorIterator($directoryIterator);
        return new RegexIterator($iteratorIterator, '/\.php$/');
    }

    /**
     * Returns full path to views directory
     * @return string
     */
    public function getViewsDir()
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getAppRoot(), $this->viewsDir]);
    }

    /**
     * Returns a Twig Environment instance for rendering templates
     * @param string $directory
     * @return Environment
     */
    public function getTwigLoader()
    {
        $loader = new FilesystemLoader($this->getViewsDir());
        return new Environment($loader);
    }
}
