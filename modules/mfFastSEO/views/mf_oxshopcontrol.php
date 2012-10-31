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
     * Main shop manager, that sets shop status, executes configuration methods.
     * Executes oxShopControl::_runOnce(), if needed sets default class (according
     * to admin or regular activities).
     *
     * Session variables:
     * <b>actshop</b>
     *
     * @return void
     */
    public function start()
    {
        // Sends the user with a request with a SEO url?
        if (isset($_REQUEST['mfLang'])) {
            $this->adjustRequestParams();
        }

        $this->initAutoLoader();
        $this->initPDO();

        parent::start();
    }

    /**
     * Manipulate request param, if our SEO implementation is used.
     *
     * @return void
     */
    private function adjustRequestParams()
    {
        // We have to fetch the language ID, because we're using ISO-3166-1 codes instead of an internal ID.
        $oxidLang           = oxLang::getInstance();
        $newLanguageId      = array_search($_REQUEST['mfLang'], $oxidLang->getLanguageIds());
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
        $dsn = sprintf(
            'mysql:dbname=%s;host=%s',
            $oxidConfig->getConfigParam('dbName'),
            $oxidConfig->getConfigParam('dbHost')
        );

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
