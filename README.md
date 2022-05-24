# Templates-Email

Package for email notifications with editable templates (for important user-related events)

[![codecov](https://codecov.io/gh/EscolaLMS/Templates-Email/branch/main/graph/badge.svg?token=O91FHNKI6R)](https://codecov.io/gh/EscolaLMS/Templates-Email)
[![Tests PHPUnit in environments](https://github.com/EscolaLMS/Templates-Email/actions/workflows/test.yml/badge.svg)](https://github.com/EscolaLMS/Templates-Email/actions/workflows/test.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/7d61484f7610611183ff/maintainability)](https://codeclimate.com/github/EscolaLMS/Templates-Email/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/7d61484f7610611183ff/test_coverage)](https://codeclimate.com/github/EscolaLMS/Templates-Email/test_coverage)
[![downloads](https://img.shields.io/packagist/dt/escolalms/templates-email)](https://packagist.org/packages/escolalms/templates-email)
[![downloads](https://img.shields.io/packagist/v/escolalms/templates-email)](https://packagist.org/packages/escolalms/templates-email)
[![downloads](https://img.shields.io/packagist/l/escolalms/templates-email)](https://packagist.org/packages/escolalms/templates-email)

## What does it do

This package allows you to add template email includes event and email variable

## Installing

- `composer require escolalms/templates-email`
## Template used

1. Create event which trigger template email, event must have method getUser(),
2. Create Class variable, which you will use in mail,
3. Register Event in provider. Use `EscolaLms\Templates\Facades\Template::register(Event class, EscolaLms\TemplatesEmail\Core\EmailChannel::class, Variable class);`
4. Register template in db for admin panel or used `/api/admin/templates`, better described in [Template package](https://github.com/EscolaLMS/Templates)

## Tests

Run `./vendor/bin/phpunit --filter 'EscolaLms\\TemplatesEmail\\Tests'` to run tests. See [tests](tests) folder as it's quite good starting point as documentation appendix.

Test details [![codecov](https://codecov.io/gh/EscolaLMS/Templates-Email/branch/main/graph/badge.svg?token=O91FHNKI6R)](https://codecov.io/gh/EscolaLMS/Templates-Email) [![Tests PHPUnit in environments](https://github.com/EscolaLMS/Templates-Email/actions/workflows/test.yml/badge.svg)](https://github.com/EscolaLMS/Templates-Email/actions/workflows/test.yml)

### Admin panel

**Left menu**

![Menu](docs/menu.png "Menu")

**List of templates**

![List of templates](docs/list.png "List of templates")

**Creating/editing template**

![Creating/editing template](docs/edit.png "Creating or editing template")
