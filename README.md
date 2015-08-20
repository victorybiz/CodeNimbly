# CodeNimbly

An extensible micro HMVC framework for PHP newbies and crafters.

## Requirements
* PHP 5.4 or greater
* Apache mod_rewrite module

## License

CodeNimbly is released under the MIT license.

## Installation
1\. [Download CodeNimbly](https://github.com/compudeluxe/CodeNimbly/archive/master.zip) and extract to your web directory.

2\. Configure your webserver.

For *Apache*, edit your `.htaccess` file with the following:

```
<IfModule mod_rewrite.c>
    Options +FollowSymLinks
    RewriteEngine On    
    RewriteBase /
    RewriteRule ^index\.php$ - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d    
    RewriteRule . /index.php [QSA,L]
</IfModule>
```

For *Nginx*, add the following to your server declaration:

```
server {
    location / {
        try_files $uri $uri/ /index.php;
    }
}
```

3\. Goto `app/config/*.php` and configure your application and set your `$config['base_url']` in `app/config/app.php`

4\. Point your browser to your `$config['base_url']` and hopefully see your starting page. CodeNimbly is bundled with [Bootstrap](http://getbootstrap.com) 3 and [JQuery](http://jquery.com) v2.1.1.

## Documentation

Documentation coming soon.

