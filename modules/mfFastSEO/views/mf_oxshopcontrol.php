<?php
/**
 * Module to manipulate request data, if the user uses our new SEO implementation.
 *
 * @category   OXID eShop
 * @package    Modules
 * @author     Stefan Krenz <stefan.krenz@mayflower.de>
 */

/**
 * Module to manipulate request data, if the user uses our new SEO implementation.
 */
class mf_oxshopcontrol extends mf_oxshopcontrol_parent
{
    /**
     * Module initialization.
     *
     * Overriding oxShopControl::start() method isn't possible if PHP strict standards are enabled.
     *
     * @return void
     */
    protected function _runOnce()
    {
        // The user used a SEO-URL request?
        if (isset($_REQUEST['mfLang'])) {
            $this->adjustRequestParams();
        }

        $this->initAutoLoader();
        $this->initPDO();

        parent::_runOnce();
    }

    /**
     * Manipulate request param, if our SEO implementation is used.
     *
     * @return void
     */
    private function adjustRequestParams()
    {
        // We have to fetch the language ID, because we're using ISO-3166-1 codes instead of an internal ID.
        $oxidLang      = oxLang::getInstance();
        $locale        = $_REQUEST['mfLang'];
        $newLanguageId = array_search($locale, $oxidLang->getLanguageIds());

        if (false === $newLanguageId) {
            $cleanRequestUri = str_replace(dirname($_SERVER['SCRIPT_NAME']) . '/', '', $_SERVER['REQUEST_URI']);
            $locale = substr($cleanRequestUri, 0, strpos($cleanRequestUri, '/'));
            $newLanguageId = array_search($locale, $oxidLang->getLanguageIds());
        }

        $availableLanguages = $oxidLang->getLanguageArray(null, true);

        // If the language is active, switch to them.
        if (isset($availableLanguages[$newLanguageId])) {
            $oxidLang->setBaseLanguage($newLanguageId);
            $oxidLang->setTplLanguage($newLanguageId);
        }

        if (isset($_GET['pgNr'])) {
            $_GET['pgNr']--;
        }

        if (isset($_POST['pgNr'])) {
            $_POST['pgNr']--;
        }
    }

    /**
     * Initializes the auto-loader.
     *
     * @return void
     */
    private function initAutoLoader()
    {
        $libraryPath = realpath(dirname(__FILE__) . '/../library');
        include_once $libraryPath . "/Mayflower/Loader/Autoloader.php";

        $autoloader = new Mayflower_Loader_Autoloader();
        $autoloader->addPath($libraryPath);
        $autoloader->registerAutoloader();
    }

    /**
     * Initializes a new PDO instance for our models.
     *
     * @return void
     */
    private function initPDO()
    {
        $oxidConfig = oxConfig::getInstance();

        $dbHost = $oxidConfig->getConfigParam('dbHost');

        $colonPosition = strpos($dbHost, ':');
        $colonExists   = (false !== $colonPosition);
        $slashPosition = strpos($dbHost, '/');
        $slashExists   = (false !== $slashPosition);

        $connectionTarget = array(
            '{NAME}'  => 'host',
            '{VALUE}' => $dbHost,
        );

        if ($colonExists && $slashExists && $slashPosition > $colonPosition) {
            $connectionTarget['{NAME}']  = 'unix_socket';
            $connectionTarget['{VALUE}'] = substr($dbHost, $colonPosition + 1);
        }

        $dsn = sprintf(
            'mysql:dbname=%s;{NAME}={VALUE}',
            $oxidConfig->getConfigParam('dbName')
        );

        $dsn = strtr($dsn, $connectionTarget);

        $pdo = new PDO(
            $dsn,
            $oxidConfig->getConfigParam('dbUser'),
            $oxidConfig->getConfigParam('dbPwd'),
            array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            )
        );

        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        Mayflower_Model_DbAbstract::setDefaultDbAdapter($pdo);
    }
}
