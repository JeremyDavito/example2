# EshopProjectBack

Notes à moi-même: 
Install Pdo postgre (apt-get install php8.1-pgsql )
 

php bin/console dbal:run-sql 'SELECT * FROM brand'

php bin/console debug:router 

php bin/console doctrine:migrations:diff

composer require symfonycasts/reset-password-bundle
php bin/console make:reset-password


https://packagist.org/packages/knplabs/knp-paginator-bundle



Jwt
<!-- 
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa
_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem
 -pubout -->
 

