# DEVELOPER Notes for datadesl

## Internal server error 500

datadesk has a built in routing system that makes use of a .htaccess file that redirects requests to routing.php in order to manage navigation in the site

This error may occur if the server hosting datadesk does not allow overrides in the sites directory.
This can be easily fixed. In the apache2.conf file, usually found in the /etc/apache2 directory, add a security model to the list of directories found in the file:

```
<Directory /var/www/[path to site]/>
    AllowOverride All
</Directory>
```

This will give the necessary permissions to step in and handle incoming requests to datadesk 