URL Rewrite with HTACCESS

If you want to make the URL user-friendly, use HTACCESS with RewriteRule. With mod_rewrite, you can make the URL length shorter and easy to share.

Create a .htaccess file and add the following code.

<IfModule mod_rewrite.c> RewriteEngine On RewriteCond %{REQUEST_FILENAME} !-f RewriteCond %{REQUEST_FILENAME} !-d RewriteRule ^([a-zA-Z0-9]+)/?$ redirect.php?c=$1 [L] </IfModule>

The above HTACCESS RewriteRule will send the request to redirect.php file. So, the redirect.php file name doesn’t need to mention in the short URL.

The short URL without using HTACCESS looks something like this – https://example.com/redirect.php?c=abcdlk


The short URL with using HTACCESS looks something like this – https://example.com/abcdlk

