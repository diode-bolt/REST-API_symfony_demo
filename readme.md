```
composer install
```
```
php bin/console app:regenerate-secret
```
```
php bin/console lexik:jwt:generate-keypair --overwrite
```
```
php bin/console doctrine:migrations:migrate
```
```
php bin/console app:users:make-admin
```
