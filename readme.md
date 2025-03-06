# REST API symfony demo

## Platform requirements
+ composer-plugin-api   2.2.0                                               
+ composer-runtime-api  2.2.2                                               
+ ext-ctype             8.1.31                                              
+ ext-date              8.1.31                                              
+ ext-dom               2.9.14                                            
+ ext-filter            8.1.31                                              
+ ext-hash              8.1.31                                              
+ ext-iconv             8.1.31                                              
+ ext-json              8.1.31                                              
+ ext-libxml            8.1.31                                              
+ ext-mbstring          * 
+ ext-openssl           8.1.31                                              
+ ext-pcre              8.1.31                                        
+ ext-phar              8.1.31                                        
+ ext-sodium            8.1.31                                        
+ ext-spl               8.1.31                                        
+ ext-tokenizer         8.1.31                                        
+ ext-xml               8.1.31                                        
+ ext-xmlwriter         8.1.31                                        
+ php                   8.1.31

## Start project

1. **Create file `.env` in root directory. transfer variables from `.env.example` file to it and replace them with your own.**

2.  **Install dependencies** 
    ```
    composer install
    ```
3. **Generate secret keys**
    ```
    php bin/console app:regenerate-secret
    ```
4. **Create JWT token keys**
    ```
    php bin/console lexik:jwt:generate-keypair --overwrite
    ```
5. **Configure connect to database ([guide](https://symfony.com/doc/current/doctrine.html#configuring-the-database)) and apply migration**
    ```
    php bin/console doctrine:migrations:migrate
    ```
   
6. **Create Admin user**
    ```
    php bin/console app:users:make-admin
    ```
7. **Configure server or use php(```php -S localhost:8000 -t public/```)/ symfony sli(```symfony server:start```)**


8. **Open homepage**
