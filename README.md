# prooph/common

[![Build Status](https://travis-ci.org/prooph/common.svg?branch=master)](https://travis-ci.org/prooph/common)
[![Coverage Status](https://coveralls.io/repos/prooph/common/badge.svg?branch=master)](https://coveralls.io/r/prooph/common?branch=master)
[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/prooph/improoph)

Common classes shared between prooph components

## Shared Kernel

Prooph components work with [php-fig](http://www.php-fig.org/) standards and other de facto standards like [Container-Interop](https://github.com/container-interop/container-interop) whenever possible.
But they also share some prooph software specific classes. These common classes are included in this repository.

## Documentation

Documentation is in the doc tree, and can be compiled using bookdown.

$ php ./vendor/bin/bookdown docs/bookdown.json
$ php -S 0.0.0.0:8080 -t docs/html/

Then browse to http://localhost:8080/

## Support

- Ask questions on [prooph-users](https://groups.google.com/forum/?hl=de#!forum/prooph) google group.
- File issues at [https://github.com/prooph/common/issues](https://github.com/prooph/common/issues).

## Contribute

Please feel free to fork and extend existing or add new features and send a pull request with your changes!
To establish a consistent code quality, please provide unit tests for all your changes and may adapt the documentation.


