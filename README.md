mfFastSEO
=========

Replaces OXIDs SEO-URL handling.

Requirements
------------

OXID-Version >= 4.6.0


Installation
------------

Add the following lines to your .htaccess, after the line
`RewriteRule oxseo\.php$ oxseo.php?mod_rewrite_module_is=on [L]`

```.htaccess
RewriteCond %{REQUEST_URI} ^/([^/]*)/.*/?product-([^/]*)/category-([^/]*)\.html$
RewriteRule \.html$ /index.php?cl=details&mfLang=%1&anid=%2&cnid=%3 [L]

RewriteCond %{REQUEST_URI} ^/([^/]*)/.*/?category-([^/]*)/product-([^/]*)\.html$
RewriteRule \.html$ /index.php?cl=details&mfLang=%1&anid=%3&cnid=%2 [L]

RewriteCond %{REQUEST_URI} ^/([^/]*)/.*/?category-([^-/]*)-?(\d*)?\.html$
RewriteRule \.html$ /index.php?cl=alist&mfLang=%1&cnid=%2&pgNr=%3 [L]

RewriteCond %{REQUEST_URI} ^/([^/]*)/.*/?product-([^/]*)\.html$
RewriteRule \.html$ /index.php?cl=details&mfLang=%1&anid=%2 [L]
```
