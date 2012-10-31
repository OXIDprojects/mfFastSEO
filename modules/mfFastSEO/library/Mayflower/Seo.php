<?php
/**
 * Class for generating and fetching SEO URLs.
 *
 * @category   OXID eShop
 * @package    Modules
 * @author     Stefan Krenz <stefan.krenz@mayflower.de>
 */

/**
 * Class for generating and fetching SEO URLs.
 */
class Mayflower_Seo extends Mayflower_Model_DbAbstract
{
    /**
     * Request for a SEO URL of a product.
     *
     * @const int
     */
    const PRODUCT_URL = 1;

    /**
     * Request for a SEO URL of a category.
     *
     * @const int
     */
    const CATEGORY_URL = 2;

    /**
     * Request for a SEO URL of a product in a specific category.
     *
     * @const int
     */
    const PRODUCT_CATEGORY_URL = 3;

    /**
     * SEO URL cache (per request)
     *
     * @var array
     */
    private static $_requestCache = array();

    /**
     * Retrieves a SEO URL from the database.
     *
     * @param Mayflower_Seo_Url $seoUrlObject Instance of an SEO URL class, used to transfer the data.
     *
     * @return bool
     */
    public function fetchSeoUrl(Mayflower_Seo_Url $seoUrlObject)
    {
        $cacheKey = $this->_getCacheKey($seoUrlObject);
        if (isset(self::$_requestCache[$cacheKey])) {
            return self::$_requestCache[$cacheKey];
        }

        $where = '';

        $rowData = array(
            'languageId' => $seoUrlObject->languageId,
        );

        switch ($seoUrlObject->type) {
            case self::PRODUCT_URL:
                $where           = "`productOxid` = :oxid AND `categoryOxid` = ''";
                $rowData['oxid'] = $seoUrlObject->productOxid;
                break;
            case self::CATEGORY_URL:
                $where           = "`categoryOxid` = :oxid AND `page` = :page AND `productOxid` = ''";
                $rowData['oxid'] = $seoUrlObject->categoryOxid;
                $rowData['page'] = $seoUrlObject->pageNo;
                break;
            case self::PRODUCT_CATEGORY_URL:
                $where                   = '`productOxid` = :productOxid AND `categoryOxid` = :categoryOxid';
                $rowData['productOxid']  = $seoUrlObject->productOxid;
                $rowData['categoryOxid'] = $seoUrlObject->categoryOxid;
        }

        if (empty($where)) {
            return false;
        }

        $sql      = "SELECT `seoUrl` FROM `mfSeoUrls` WHERE $where AND `languageId` = :languageId";
        $pdoState = $this->_dbAdapter->prepare($sql);
        $pdoState->execute($rowData);
        $seoUrl = $pdoState->fetchColumn(0);

        if ($seoUrl === false) {
            return false;
        }

        $seoUrlObject->seoUrl           = $seoUrl;
        self::$_requestCache[$cacheKey] = $seoUrlObject->seoUrl;

        return true;
    }

    /**
     * Generates the key for the request based cache.
     *
     * @param Mayflower_Seo_Url $seoUrlObject Information about the SEU URL.
     *
     * @return string
     */
    private function _getCacheKey($seoUrlObject)
    {
        return sprintf(
            "%u-%s-%s-%u",
            $seoUrlObject->languageId,
            $seoUrlObject->productOxid,
            $seoUrlObject->categoryOxid,
            $seoUrlObject->pageNo
        );
    }

    /**
     * Generate a SEO URL.
     *
     * @param Mayflower_Seo_Url $seoUrlObject        Required information for the generation.
     * @param string            $langIsoCode         ISO code of the language.
     * @param string|null       $seoReplacementsFile Name of the file for special char replacements.
     *
     * @return void
     */
    public function generateSeoUrl(Mayflower_Seo_Url $seoUrlObject, $langIsoCode, $seoReplacementsFile = null)
    {
        $seoReplacement = array();
        if ($seoReplacementsFile !== null) {
            $seoReplacement = include($seoReplacementsFile);
        }

        $seoUrlObject->seoUrl = "$langIsoCode/";
        $categorySeoPartial   = '';
        $productSeoPartial    = '';

        $categorySuffix = '';
        $productSuffix  = '';

        if (($seoUrlObject->type & self::PRODUCT_URL) == self::PRODUCT_URL) {
            $productSeoPartial = $this->generateProductSeoPartial(
                $seoUrlObject->productOxid,
                $langIsoCode,
                $seoReplacement
            );
            $productSuffix     = "product-{$seoUrlObject->productOxid}.html";
        }

        if (($seoUrlObject->type & self::CATEGORY_URL) == self::CATEGORY_URL) {
            $categorySeoPartial = $this->generateCategorySeoPartial(
                $seoUrlObject->categoryOxid,
                $langIsoCode,
                $seoReplacement
            );
            $categorySeoPartial .= '/';

            $categorySuffix = "category-{$seoUrlObject->categoryOxid}";

            if ($seoUrlObject->type == self::CATEGORY_URL) {
                $categorySuffix .= "-{$seoUrlObject->pageNo}.html";
            } else {
                $categorySuffix .= '/';
            }
        }

        $seoUrlObject->seoUrl .= $categorySeoPartial . $productSeoPartial . $categorySuffix . $productSuffix;

        $cacheKey                       = $this->_getCacheKey($seoUrlObject);
        self::$_requestCache[$cacheKey] = $seoUrlObject->seoUrl;
    }

    /**
     * Generates a partial URL for a product.
     *
     * @param string $productOxid    OXID of the product.
     * @param string $langIsoCode    ISO code of the language.
     * @param array  $seoReplacement Array for special char replacements.
     *
     * @return string
     */
    protected function generateProductSeoPartial($productOxid, $langIsoCode, $seoReplacement)
    {
        $sql      = "SELECT `OXTITLE` FROM `oxv_oxarticles_$langIsoCode` WHERE `OXID` = ?";
        $pdoState = $this->_dbAdapter->prepare($sql);
        $pdoState->execute(array($productOxid));

        $title = $pdoState->fetchColumn();
        $title = strtr($title, $seoReplacement);

        $seoUrlPartial = "$title/";
        return $seoUrlPartial;
    }

    /**
     * Generates a partial URL for a category.
     *
     * @param string $categoryOxid   OXID of the category.
     * @param string $langIsoCode    ISO code of the language.
     * @param array  $seoReplacement Array for special char replacements.
     *
     * @return string
     */
    protected function generateCategorySeoPartial($categoryOxid, $langIsoCode, $seoReplacement)
    {
        $viewName = "oxv_oxcategories_$langIsoCode";
        $sql      = "SELECT catData.`OXTITLE`
            FROM `$viewName` catData, `$viewName` catSelect
            WHERE
                catData.`OXLEFT` <= catSelect.`OXLEFT` AND
                catData.`OXRIGHT` > catSelect.`OXLEFT` AND
                catData.`OXROOTID` = catSelect.`OXROOTID` AND
                catSelect.`OXID` = ?
            GROUP BY catData.`OXLEFT`
            ORDER BY catData.`OXLEFT` ASC";
        $pdoState = $this->_dbAdapter->prepare($sql);
        $pdoState->execute(array($categoryOxid));
        $titles = $pdoState->fetchAll(\PDO::FETCH_COLUMN);
        $title  = strtr(
            implode('/', $titles),
            $seoReplacement
        );

        $seoUrlPartial = "$title";
        return $seoUrlPartial;
    }

    /**
     * Puts a SEO URL into the database.
     *
     * @param Mayflower_Seo_Url $seoUrlObject Information about the SEO URL.
     *
     * @return bool
     */
    public function saveSeoUrl(Mayflower_Seo_Url $seoUrlObject)
    {
        $sql         = "INSERT INTO `mfSeoUrls` (`languageId`, `productOxid`, `categoryOxid`, `page`, `seoUrl`) VALUES
            (:languageId, :productOxid, :categoryOxid, :page, :seoUrl)";
        $insertState = $this->_dbAdapter->prepare($sql);
        $rowData     = array(
            'languageId'   => $seoUrlObject->languageId,
            'productOxid'  => ($seoUrlObject->productOxid !== null) ? $seoUrlObject->productOxid : '',
            'categoryOxid' => ($seoUrlObject->categoryOxid !== null) ? $seoUrlObject->categoryOxid : '',
            'page'         => $seoUrlObject->pageNo,
            'seoUrl'       => $seoUrlObject->seoUrl,
        );

        return $insertState->execute($rowData);
    }
}
