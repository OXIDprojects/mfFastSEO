<?php
/**
 * Object-Value class for generating and fetching SEO URLs.
 *
 * @category   OXID eShop
 * @package    Modules
 * @author     Stefan Krenz <stefan.krenz@mayflower.de>
 */

/**
 * Object-Value class for generating and fetching SEO URLs.
 */
class Mayflower_Seo_Url
{
    /**
     * Type of the SEO URL.
     *
     * @see Mayflower_SEO::PRODUCT_URL
     * @see Mayflower_SEO::CATEGORY_URL
     * @see Mayflower_SEO::PRODUCT_CATEGORY_URL
     *
     * @var int
     */
    public $type;

    /**
     * OXIDs language ID.
     *
     * @var int
     */
    public $languageId;

    /**
     * OXIDs product ID (only used with the types 'PRODUCT_URL' and 'PRODUCT_CATEGORY_URL').
     *
     * @var string
     */
    public $productOxid;

    /**
     * OXIDs product ID (only used with the types 'CATEGORY_URL' and 'PRODUCT_CATEGORY_URL').
     *
     * @var string
     */
    public $categoryOxid;

    /**
     * The fetched SEO URL.
     *
     * @var string
     */
    public $seoUrl;

    /**
     * Page number (only used with the type 'CATEGORY_URL').
     *
     * @var int
     */
    public $pageNo = 1;
}
