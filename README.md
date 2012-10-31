# mfFastSEO

Replaces OXIDs SEO-URL handling.

## Requirements

You need at least the OXID-Version 4.6.0.
There are no additional requirements.


## Installation

1. Copy the file into your `/path/to/modules/` directory.
2. Patch the `.htaccess` file.
3. Run the `install.sql` in yout favourite database management program (such as MySQLDumper or phpMyAdmin).
4. Activate the module in OXID-Admin.

### Patching the `.htaccess`
Add the following lines to your .htaccess, after the line
`RewriteRule oxseo\.php$ oxseo.php?mod_rewrite_module_is=on [L]`

```htaccess
RewriteCond %{REQUEST_URI} ^/([^/]*)/.*/?product-([^/]*)/category-([^/]*)\.html$
RewriteRule \.html$ /index.php?cl=details&mfLang=%1&anid=%2&cnid=%3 [L]

RewriteCond %{REQUEST_URI} ^/([^/]*)/.*/?category-([^/]*)/product-([^/]*)\.html$
RewriteRule \.html$ /index.php?cl=details&mfLang=%1&anid=%3&cnid=%2 [L]

RewriteCond %{REQUEST_URI} ^/([^/]*)/.*/?category-([^-/]*)-?(\d*)?\.html$
RewriteRule \.html$ /index.php?cl=alist&mfLang=%1&cnid=%2&pgNr=%3 [L]

RewriteCond %{REQUEST_URI} ^/([^/]*)/.*/?product-([^/]*)\.html$
RewriteRule \.html$ /index.php?cl=details&mfLang=%1&anid=%2 [L]
```
