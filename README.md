# SimpleSAMLphp preprodwarning module

![Build Status](https://github.com/simplesamlphp/simplesamlphp-module-preprodwarning/actions/workflows/php.yml/badge.svg)
[![Coverage Status](https://codecov.io/gh/simplesamlphp/simplesamlphp-module-preprodwarning/branch/master/graph/badge.svg)](https://codecov.io/gh/simplesamlphp/simplesamlphp-module-preprodwarning)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/simplesamlphp/simplesamlphp-module-preprodwarning/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/simplesamlphp/simplesamlphp-module-preprodwarning/?branch=master)
[![Type Coverage](https://shepherd.dev/github/simplesamlphp/simplesamlphp-module-preprodwarning/coverage.svg)](https://shepherd.dev/github/simplesamlphp/simplesamlphp-module-preprodwarning)
[![Psalm Level](https://shepherd.dev/github/simplesamlphp/simplesamlphp-module-preprodwarning/level.svg)](https://shepherd.dev/github/simplesamlphp/simplesamlphp-module-preprodwarning)

## Install

Install with composer

```bash
vendor/bin/composer require simplesamlphp/simplesamlphp-module-preprodwarning
```

## Configuration

Next thing you need to do is to enable the module:

in `config.php`, search for the `module.enable` key and set `preprodwarning` to true:

```php
'module.enable' => [ 'preprodwarning' => true, … ],
```
