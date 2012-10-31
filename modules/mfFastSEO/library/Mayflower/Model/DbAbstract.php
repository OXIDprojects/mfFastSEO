<?php
/**
 * Abstract class for model that uses a database connection.
 *
 * @category   OXID eShop
 * @package    Models
 * @author     Stefan Krenz <stefan.krenz@mayflower.de>
 */

/**
 * Abstract class for model that uses a database connection.
 */
abstract class Mayflower_Model_DbAbstract
{
    /**
     * Default PDO instance.
     *
     * @var PDO
     */
    private static $_defaultDbAdapter;

    /**
     * Current PDO instance.
     *
     * @var PDO
     */
    protected $_dbAdapter;

    /**
     * Class contructor, sets the current PDO instance.
     *
     * @return Mayflower_Model_DbAbstract
     */
    public function __construct()
    {
        $this->setDbAdapter(self::getDefaultDbAdapter());
        $this->init();
    }

    /**
     * Model specific initialization method.
     *
     * This method is invoked by the class constructor and can be overwritten if a model needs a specific initalization.
     *
     * @return void
     */
    public function init()
    {
    }

    /**
     * Sets the current PDO instance.
     *
     * @param \PDO $dbAdapter Instance of the PDO class.
     *
     * @return void
     */
    public function setDbAdapter($dbAdapter)
    {
        $this->_dbAdapter = $dbAdapter;
    }

    /**
     * Returns the current PDO instance.
     *
     * @return \PDO
     */
    public function getDbAdapter()
    {
        return $this->_dbAdapter;
    }

    /**
     * Sets the default PDO instance.
     *
     * @param \PDO $defaultDbAdapter Instance of the PDO class.
     *
     * @return void
     */
    public static function setDefaultDbAdapter($defaultDbAdapter)
    {
        self::$_defaultDbAdapter = $defaultDbAdapter;
    }

    /**
     * Returns the default PDO instance.
     *
     * @return \PDO
     */
    public static function getDefaultDbAdapter()
    {
        return self::$_defaultDbAdapter;
    }

}
