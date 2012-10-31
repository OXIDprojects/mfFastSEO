<?php
/**
 * Module to inject our new SEO implementation for products.
 *
 * @category   OXID eShop
 * @package    Modules
 * @author     Stefan Krenz <stefan.krenz@mayflower.de>
 */

/**
 * Module to inject our new SEO implementation for products.
 */
class mf_oxarticle extends mf_oxarticle_parent
{
    /**
     * Returns raw product seo url.
     *
     * @param int  $iLang  Language ID.
     * @param bool $blMain Force to return main url. [optional]
     *
     * @return string
     */
    public function getBaseSeoLink($iLang, $blMain = false)
    {
        // Set the required data to retrieve the data.
        $seoUrl = new Mayflower_Seo_Url();
        $seoUrl->type = Mayflower_Seo::PRODUCT_URL;
        $seoUrl->languageId = $iLang;
        $seoUrl->productOxid = $this->getId();

        $category = $this->getCategory();
        if ($category !== null) {
            $seoUrl->categoryOxid = $category->getId();
            $seoUrl->type = Mayflower_Seo::PRODUCT_CATEGORY_URL;
        }

        // Fetch the SEO-URL.
        $seo = new Mayflower_Seo();
        $fetchResult = $seo->fetchSeoUrl($seoUrl);

        if (!$fetchResult) {
            $seoReplaceFile = getShopBasePath() . 'modules/mfFastSEO/core/translits.php';
            $seo->generateSeoUrl($seoUrl, oxLang::getInstance()->getLanguageAbbr($iLang), $seoReplaceFile);
            $seo->saveSeoUrl($seoUrl);
        }

        $baseSeoLink = oxConfig::getInstance()->getSslShopUrl() . $seoUrl->seoUrl;

        return $baseSeoLink;
    }
}
