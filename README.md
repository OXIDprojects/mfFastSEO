# mfFastSEO

Replaces OXIDs SEO-URL handling.

## Requirements

You need at least the OXID-Version 4.6.0.
There are no additional requirements.


## Installation

1. Copy the `modules` directory into your shop directory.
2. Patch the `.htaccess` file (see below).
3. Run the `install.sql` in your favourite database management program (such as [MySQLDumper](http://www.mysqldumper.net/), [phpMyAdmin](http://www.phpmyadmin.net/) or [The MySQL Command-Line Tool](http://dev.mysql.com/doc/refman/5.0/en/mysql.html)).
3.1. In MySQLDumper, remove the trailing semicolon.
3.2. Command-line for the MySQL Command-Line tool `mysql`: `mysql -h<DB-Host> -u<DB-User> -p<DB-Password> <Shop-Database> < modules/mfFastSEO/install/install.sql`
4. Activate the module in OXID-Admin.

### Patching the `.htaccess`
Add the following lines to your .htaccess, after the line
`RewriteRule oxseo\.php$ oxseo.php?mod_rewrite_module_is=on [L]`

```
RewriteCond %{REQUEST_URI} ^/([^/]*)/.*/?product-([^/]*)/category-([^/]*)\.html$
RewriteRule \.html$ /index.php?cl=details&mfLang=%1&anid=%2&cnid=%3 [L]

RewriteCond %{REQUEST_URI} ^/([^/]*)/.*/?category-([^/]*)/product-([^/]*)\.html$
RewriteRule \.html$ /index.php?cl=details&mfLang=%1&anid=%3&cnid=%2 [L]

RewriteCond %{REQUEST_URI} ^/([^/]*)/.*/?category-([^-/]*)-?(\d*)?\.html$
RewriteRule \.html$ /index.php?cl=alist&mfLang=%1&cnid=%2&pgNr=%3 [L]

RewriteCond %{REQUEST_URI} ^/([^/]*)/.*/?product-([^/]*)\.html$
RewriteRule \.html$ /index.php?cl=details&mfLang=%1&anid=%2 [L]
```
