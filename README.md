#  Sales API

### Feats

* Laravel 5.4
* Mysql
* Cache (simple with file)
* Queue (simple with array)
* Mail (using mailtrap)
* Query Logging using Event (only works locally!)
* Uses Dingo/API Package
* Migrations, Factories and Seeds
* Transformers, Repositories and Decorators
* Dependency Injection and Inversion of Control
* Generated API Docs [a relative link](Document.md)
* Unit (using Mockery) and Feature Tests

### Initialization

1. Clone this repository:
```
git clone https://github.com/emanuellarini/sales-api.git
```
2. Install dependencies:
```
composer install
```
3. Create a .env file from .env.example and generate key:
```
cp .env.example .env && php artisan key:generate
```
4. Create your local database
5. Change .env to your database connection settings and mail settings
6. Migrate and seed database:
```
php artisan migrate --seed
```
7. Run tests:
```
phpunit
```
8. Run command to send daily sales report (check your mailtrap inbox!):
```
php artisan email:daily-sales && php artisan queue:work
```
9. Wanna log queries?
```
tail -f storage/logs/laravel.log
```
