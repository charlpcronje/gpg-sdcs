# Notes about Security

## SSL with .htaccess

You can set your apache web-server to force `https` by adding the following

```conf
# Redirect all HTTP traffic to HTTPS
# RewriteEngine On
# RewriteCond %{HTTPS} !=on
# RewriteRule ^.*$ https://%{SERVER_NAME}%{REQUEST_URI} [R,L]
```