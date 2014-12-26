Getting Started With GOSFormBundle
==================================

By default Symfony2 provide form component, GOSFormBundle is built on top. We allowed you to simply move the process executed when your form are sent. Why ? To keep your controller clean, without logic, and to have a generic structure to handle your forms.

## Prerequisites

This bundle requires Symfony2.1 at least.

## Installation

Installation is very quick :)

1. Download GOSFormBundle using [composer](https://getcomposer.org/)
2. Enable the bundle

### Step 1 : Download GOSFormBundle using [composer](https://getcomposer.org/)

```bash
php composer.phar require gos/form-bundle "~1.0"
```

[Composer](https://getcomposer.org/) will install the bundle to your project's vendor/gos directory.

### Step 2 : Enable the bundle

Enable the bundle in the kernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Gos\Bundle\FormBundle\GosFormBundle(),
    );
}
```

## Next step

- [Use GOSFormHandler](usage.md)




