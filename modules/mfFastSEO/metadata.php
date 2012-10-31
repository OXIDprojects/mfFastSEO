<?php

$aMetadataVersion = '1.0';

$moduleId = 'mfFastSEO';

$aModule = array(
    'id'          => $moduleId,
    'title'       => array(
        'en' => 'Mayflower SEO-URL enhancement',
        'de' => 'Mayflower SEO-URL Verbesserung'
    ),
    'description' => array(
        'en' => 'This module enhances the performance of SEO-URLs.',
        'de' => 'Dieses Modul verbessert die Performance von SEO-URLs.',
    ),
    'thumbnail'   => 'mayflower.png',
    'version'     => '1.0.0',
    'author'      => 'Mayflower GmbH',
    'email'       => 'info@mayflower.de',
    'url'         => 'http://www.mayflower.de/',
    'extend'      => array(
        'oxarticle'     => $moduleId . '/core/mf_oxarticle',
        'oxcategory'    => $moduleId . '/core/mf_oxcategory',
        'oxshopcontrol' => $moduleId . '/views/mf_oxshopcontrol',
    ),
);
