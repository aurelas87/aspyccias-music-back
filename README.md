# aspyccias-music-back

BackEnd side of aspyccias-music.com website

## Project Requirements

- [PHP](https://www.php.net/) 8.3
- [MySQL](https://www.mysql.com) 8.3.0
- [Composer](https://getcomposer.org/)

## Project Setup

### Install packages

```sh
composer install
```

### Install database

```sh
php bin/console doctrine:database:create
```

### Migrate database

```sh
php bin/console doctrine:migrations:migrate
```

## Run Tests with [PHPUnit](https://phpunit.de/index.html)

First load the fixtures:

```sh
php bin/console doctrine:fixtures:load --env=test
```

Then launch the tests:


```sh
php bin/phpunit
```

<div style="background-color:#082f49;">

> The database name for tests will be automatically prefixed with `_test`.
> Its name in `.env.test` must therefore be the same as the one in `.env`.

</div>
