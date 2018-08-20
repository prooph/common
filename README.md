# prooph/common

[![Build Status](https://travis-ci.org/prooph/common.svg?branch=master)](https://travis-ci.org/prooph/common)
[![Coverage Status](https://coveralls.io/repos/prooph/common/badge.svg?branch=master)](https://coveralls.io/r/prooph/common?branch=master)
[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/prooph/improoph)

Common classes shared between prooph components

Due to a bug in PHP 7.1.3 this library is not compatible with that specific php version.

## Important

This library will receive support until December 31, 2019 and will then be deprecated.

For further information see the official announcement here: [https://www.sasaprolic.com/2018/08/the-future-of-prooph-components.html](https://www.sasaprolic.com/2018/08/the-future-of-prooph-components.html)

## Note about versions

The 4.0 release is only for the newer prooph-components (event-store v7, service-bus v6, and so on). If you are using
an older version of prooph/event-store or prooph/service bus, stick to 3.x series.

## Shared Kernel

Prooph components work with [php-fig](http://www.php-fig.org/) standards and other de facto standards like [Container-Interop](https://github.com/container-interop/container-interop) whenever possible.
But they also share some prooph software specific classes. These common classes are included in this repository.

## Documentation

Documentation is in the doc tree, and can be compiled using bookdown.

$ php ./vendor/bin/bookdown docs/bookdown.json
$ php -S 0.0.0.0:8080 -t docs/html/

Then browse to http://localhost:8080/

## Changes from 3.x series

- Minimum requirement is now PHP 7.1
- Add payload-method to Message interface
- Removed version-method from Message interface 
- Removed ActionEventListener interface
- Action Event Emitter can accept a list of available event names
- Update to ramsey/uuid 3.5.1
- Update to PHPUnit 6.0

## Support

- Ask questions on Stack Overflow tagged with [#prooph](https://stackoverflow.com/questions/tagged/prooph).
- File issues at [https://github.com/prooph/common/issues](https://github.com/prooph/common/issues).

## Contribute

Please feel free to fork and extend existing or add new features and send a pull request with your changes!
To establish a consistent code quality, please provide unit tests for all your changes and may adapt the documentation.
