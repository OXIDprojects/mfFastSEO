<?php
/**
 * Class for automatic loading of required PHP files.
 *
 * @category   OXID eShop
 * @package    Modules
 * @author     Stefan Krenz <stefan.krenz@mayflower.de>
 */

/**
 * Class for automatic loading of required PHP files.
 */
class Mayflower_Loader_Autoloader
{
    /**
     * Determines whether the autoloader is registered or not.
     *
     * @var bool
     */
    private $_loaderRegistered = false;

    /**
     * Base paths where the libraries are placed.
     *
     * @var array
     */
    private $_paths = array();

    /**
     * Adds for class lookup.
     *
     * @param string $path New path.
     *
     * @return void
     */
    public function addPath($path)
    {
        if (!in_array($path, $this->_paths)) {
            $this->_paths[] = rtrim($path, '/\\');
        }
    }

    /**
     * Registers the auto loader. Return TRUE if the autoloader has been successful registered, return FALSE otherwise.
     *
     * @return bool
     */
    public function registerAutoloader()
    {
        if (!$this->isLoaderRegistered()) {
            $this->_loaderRegistered = spl_autoload_register(array($this, 'loadClass'));
        }
    }

    /**
     * Determines whether the autoloader is registered or not.
     *
     * @return bool
     */
    public function isLoaderRegistered()
    {
        return $this->_loaderRegistered;
    }

    /**
     * Loads a class.
     *
     * @param string $className Name of the class to load.
     *
     * @return bool
     */
    public function loadClass($className)
    {
        $spacedClassName = str_replace(array('\\', '_'), ' ', $className);
        $classFilename = str_replace(' ', DIRECTORY_SEPARATOR, ucwords($spacedClassName)) . '.php';

        $classLoaded = false;

        foreach ($this->_paths as $path) {
            $filename = $path . DIRECTORY_SEPARATOR . $classFilename;
            if (!file_exists($filename)) {
                continue;
            }

            include_once $filename;

            if (class_exists($className)) {
                $classLoaded = true;
                break;
            }
        }

        return $classLoaded;
    }
}
