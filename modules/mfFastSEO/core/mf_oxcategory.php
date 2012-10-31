<?php
/**
 * Module to inject our new SEO implementation for categories.
 *
 * @category   OXID eShop
 * @package    Modules
 * @author     Stefan Krenz <stefan.krenz@mayflower.de>
 */

/**
 * Module to inject our new SEO implementation for categories.
 */
class mf_oxcategory extends mf_oxcategory_parent
{
    /**
     * Returns raw category seo url.
     *
     * @param int $iLang Language ID.
     * @param int $iPage Page number. [optional]
     *
     * @return string
     */
    public function getBaseSeoLink($iLang, $iPage = 0)
    {
        // Set the required data to retrieve the data.
        $seoUrl = new Mayflower_Seo_Url();
        $seoUrl->type = Mayflower_Seo::CATEGORY_URL;
        $seoUrl->languageId = $iLang;
        $seoUrl->categoryOxid = $this->getId();

        $seoUrl->pageNo = $iPage;
        $seoUrl->pageNo++;

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
