#Gos Form Bundle#

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/GeniusesOfSymfony/FormBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/GeniusesOfSymfony/FormBundle/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/GeniusesOfSymfony/FormBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/GeniusesOfSymfony/FormBundle/?branch=master)

**This project is currently in development, please take care.**

Installation
-------------

You need to have [composer](https://getcomposer.org/) to install dependencies.

```json
{
    "require": {
        "gos/form-bundle": "{last stable version}"
    }
}
```

Then run the command on the root of your project`composer update`

Add this line in your `AppKernel.php`

```php
$bundles = array(

	//Other bundles
    new Gos\Bundle\FormBundle\GosFormBundle(),
);
```

Running the tests:
------------------

PHPUnit 3.5 or newer together with Mock_Object package is required. To setup and run tests follow these steps:

* go to the root directory of fixture
* run: composer install --dev
* run: phpunit

License
---------

The project is under MIT lisence, for more information see the LICENSE file inside the project
