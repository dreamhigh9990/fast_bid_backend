# PHP-Backend
PHP Backend is necessary for backend-ui-ngx.
It has own administation pages, but it doesn't work and we use backend-ui-ngx as an administration.
This backend provides APIs for backend-ui-ngx and iphone application(old version: ios-source).
It is based on codeignitor framework.

## Installation

### Composer install

### Configuration
We need to rename local-config.php for local development environment and server-config.php for server environment as config.php.
Do the similar action to database.php also.
In the config.php file, edit base url and elasticsearch server configuration.
In the database.php file, edit database connection info.
And we need to rename server.htaccess file with .htaccess.

## Login
Default admin user info:
    username: admin
    email: 
    password: admin