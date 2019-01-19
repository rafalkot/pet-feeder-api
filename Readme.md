# Pet-feeder API

[![Build Status](https://travis-ci.org/rafalkot/pet-feeder-api.svg?branch=master)](https://travis-ci.org/rafalkot/pet-feeder-api)

Web API for mobile app - Pet feeder

## Getting started

### Requirements

- Docker
- openssl

### Installing

Clone repository into local environment

```
git clone https://github.com/rafalkot/pet-feeder-api
cd pet-feeder-api
```
Build containers and login into php-fpm

```
make up
make jwt-setup
make shell # login into php-fpm container
make install # on php-fpm container
```

### Running the tests

Test types:
- Domain tests - PHPSpec
- Integration tests - PHPUnit
- API tests - Behat

Run this command to run all the tests:

```
make test
```
Or run test suites separately:
```
make test-domain
make test-integration
make test-api
```

## Built with

- PHP 7.2 & Symfony 4.2
- MySQL
- Docker - [jorge07/alpine-php](https://github.com/jorge07/alpine-php)

Main dependencies:
- [FOSRestBundle](https://symfony.com/doc/master/bundles/FOSRestBundle/index.html) - Rest API
- [NelmioApiDocBundle](https://symfony.com/doc/current/bundles/NelmioApiDocBundle/index.html) - API docs
- [Prooph](http://getprooph.org) - Command bus
- [PHPSpec](http://www.phpspec.net/), [PHPUnit](https://phpunit.de), [Behat](http://behat.org/)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

