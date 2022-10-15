# Rewrite Rules

For this app I typically want the URL to look like this

```html
https://submit.domain.com/newPatient
```

But there won't be a file called `newPatient`, All requests wil for sent to the `index.php`
with the following code that we add to `.htaccess`


```conf
RewriteEngine On

# Remove trailing slash
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)/$ /$1 [L,R] # <- for test, for prod use [L,R=301]

# Send all request to the index file
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^([^/]*)(.*)$ index.php?controller=$1&params=$2 [QSA]
```