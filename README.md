This OXID module replaces OXIDs SEO-URL handling.

## Features
- Resolving the SEO URLs is done by `mod_rewrite`. A databse query on the table `oxseo` is no longer required/executed.
- SEO URLs are cached in database, to reduce generating of SEO URLs.
- Suffix for duplicate URLs is no longer required.
- URLs are cached per request in a static property.

## Requirements

You need at least the OXID eShop version 4.6.0.<br>
There are no additional requirements.

## Installation

*Please note the installation hints below.*

1. Copy the `modules` directory into your shop directory.
2. Patch the `.htaccess` file (see below).
3. Run the `install.sql` in your favourite database management program.<br>
(such as [MySQLDumper](http://www.mysqldumper.net/), [phpMyAdmin](http://www.phpmyadmin.net/) or [The MySQL Command-Line Tool](http://dev.mysql.com/doc/refman/5.0/en/mysql.html))
4. Activate the module in OXID-Admin.

### Installation hints

#### Patching the `.htaccess` file
Add the following lines to your .htaccess, after the line<br>
`RewriteRule oxseo\.php$ oxseo.php?mod_rewrite_module_is=on [L]`

```
RewriteCond %{REQUEST_URI} ^/([^/]*)/.*/?product-([^/]*)/category-([^/]*)\.html$
RewriteRule \.html$ index.php?cl=details&mfLang=%1&anid=%2&cnid=%3 [L]

RewriteCond %{REQUEST_URI} ^/([^/]*)/.*/?category-([^/]*)/product-([^/]*)\.html$
RewriteRule \.html$ index.php?cl=details&mfLang=%1&anid=%3&cnid=%2 [L]

RewriteCond %{REQUEST_URI} ^/([^/]*)/.*/?category-([^-/]*)-?(\d*)?\.html$
RewriteRule \.html$ index.php?cl=alist&mfLang=%1&cnid=%2&pgNr=%3 [L]

RewriteCond %{REQUEST_URI} ^/([^/]*)/.*/?product-([^/]*)\.html$
RewriteRule \.html$ index.php?cl=details&mfLang=%1&anid=%2 [L]
```

#### Updating database 
In MySQLDumper, remove the trailing semicolon.<br>
Command-line for the MySQL Command-Line tool `mysql`:
`mysql -h<DB-Host> -u<DB-User> -p<DB-Password> <Shop-Database> < modules/mfFastSEO/install/install.sql`

## TODO
- **IMPORTANT:** Support for paths in Shop-URL (e.g. http://www.example.com/path/to/shop/)
- Add an admin module to manage the SEO URLs:
 - Generate SEO URLs for the products.
 - Generate SEO URLs for the products in their categories.
 - Generate SEO URLs for the categories.
 - Generate all SEO URLs.
 - Delete SEO URLs.
