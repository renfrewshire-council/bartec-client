# Renfrewshire Council - Bartec Collective

A library for the [Bartec Collective](https://www.bartecmunicipal.com/software/collective/) SOAP Web Service. It is forked from [London Borough of Hounslow - Bartec Collective](https://github.com/LBHounslow/bartec).

This library includes both a [Client](src/Client/Client.php) and a [BartecService](src/Service/BartecService.php). The library is compatible with [v16](https://collectiveapi.bartec-systems.com/API-R1616/collectiveapi.asmx?WSDL) of the Bartec Collective API.

The [BartecService](src/Service/BartecService.php) has several methods that are used regularly. For any other call you can use the [Client](src/Client/Client.php) directly.

For more on how to use this client, see [usage documentation](docs/USAGE.md)

## Releases

- These are covered in [the Changelog](docs/CHANGELOG.md)

## Requirements

- [PHP 8.3+](https://www.php.net/downloads.php)
- [Git](https://git-scm.com/downloads)
- [Composer](https://getcomposer.org)

## Setup

- `composer install`

## Tests

Update the constants in [BartecServiceTest](tests/functional/Service/BartecServiceTest.php) for functional tests.

Run all tests

`composer test`
