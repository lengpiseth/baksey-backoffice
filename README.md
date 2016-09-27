Baksey Backend - Application
============================


A Symfony project created on April 1st, 2016, 1:41 pm.

Requirements
------------

  * PHP 5.5 or higher;
  * php-mongo
  * php-intl
  * and the [usual Symfony application requirements](http://symfony.com/doc/current/reference/requirements.html).

Assets
------
```bash
# Install bower components
$ bower install

# Install Gulp components
$ npm install
```

Phpunit
-------
```bash
# run all tests of the application
$ phpunit -c app

# run all tests in the Util directory
$ phpunit -c app src/AppBundle/Tests/Util

# run tests for the Calculator class
$ phpunit -c app src/AppBundle/Tests/Util/CalculatorTest.php

# run all tests for the entire Bundle
$ phpunit -c app src/AppBundle/
```

References
----------
**Responsive Tiled Photo Gallery.** <a href="http://jsbin.com/utupuw/5/edit?html,output">Demo</a>