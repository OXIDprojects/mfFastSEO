CREATE TABLE IF NOT EXISTS `mfSeoUrls` (
    `languageId` TINYINT NOT NULL,
    `productOxid` VARCHAR(32) NOT NULL,
    `categoryOxid` VARCHAR(32) NOT NULL,
    `page` INT NOT NULL,
    `seoUrl` TEXT NOT NULL,
    PRIMARY KEY (`languageId`, `productOxid`, `categoryOxid`, `page`),
    INDEX `productCategoryLookup` (`productOxid`, `categoryOxid`, `languageId`),
    INDEX `productLookup` (`productOxid`, `languageId`),
    INDEX `categoryLookup` (`categoryOxid`, `languageId`)
);
